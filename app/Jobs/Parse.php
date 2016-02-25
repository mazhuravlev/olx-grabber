<?php

namespace App\Jobs;

use App\Events\OfferParsed;
use App\Models\GrabbedUrl;
use App\Models\Location;
use App\Models\Offer;
use App\System\ParserLoggerInterface;
use Carbon\Carbon;
use Event;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use InvalidArgumentException;
use Mockery\CountValidator\Exception;
use Symfony\Component\DomCrawler\Crawler;

class Parse extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    private $url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(GrabbedUrl $url)
    {
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ParserLoggerInterface $logger, Client $client)
    {
        $context = [
            'url' => $this->url->url
        ];
        try {
            $response = $client->get($this->url->url, ['allow_redirects' => false]);
        } catch (ClientException $e) {
            $logger->error('Client error', self::arrayInsert($context, 'exception', $e));
            throw $e;
        }
        if (200 !== $code = $response->getStatusCode()) {
            switch ($code) {
                case 301:
                    if (preg_match('/from404/', $response->getHeaderLine('Location'))) {
                        $logger->warning(
                            'Offer deleted',
                            $context
                        );
                    } else {
                        $grabbedUrl = null;
                        try {
                            $grabbedUrl = GrabbedUrl::create(
                                [
                                    'url' => $response->getHeaderLine('Location')
                                ]
                            );
                        } catch (QueryException $e) {
                            return;
                        }
                        if ($grabbedUrl) {
                            $this->dispatch(new Parse($grabbedUrl));
                            $logger->info('Dispatched new job', self::arrayInsert($context, 'new_url', $response->getHeaderLine('Location')));
                        }
                    }
                    return;
                default:
                    $logger->error('Invalid response code ' . $code, $context);
            }
        }
        $responseHtml = $response->getBody()->getContents();
        $crawler = new Crawler($responseHtml);
        $offer = new Offer();
        $offer->href = $this->url->url;
        $requiredFieldsCommands =
            [
                'phones' => function () use ($client) {
                    return self::getPhones($client, self::getOlxId($this->url->url));
                },
                'cat_path' => function () use ($responseHtml) {
                    return self::getCatPath($responseHtml);
                },
            ];
        foreach ($requiredFieldsCommands as $field => $command) {
            try {
                $offer->$field = $command();
            } catch (\Exception $e) {
                $logger->error('Unable to extract required fields', self::arrayInsert($context, 'required_field', $field));
                throw $e;
            }
        }
        $fieldsCommands = [
            'price_string' => function () use ($crawler) {
                return $crawler->filter('.pricelabel.tcenter')->first()->text();
            },
            'title' => function () use ($crawler) {
                return $crawler->filter('.offerheadinner > h1')->text();
            },
            'olx_id' => function () {
                return self::getOlxId($this->url->url);
            },
            'description' => function () use ($crawler) {
                return $crawler->filter('#textContent')->text();
            },
            'date_string' => function () use ($crawler) {
                return self::getDate($crawler);
            },
            'offer_number' => function () use ($crawler) {
                return self::getOfferNumber($crawler);
            },
        ];
        $failedFields = [];
        foreach ($fieldsCommands as $field => $command) {
            try {
                $offer->$field = trim($command());
            } catch (\Exception $e) {
                array_push($failedFields, $field);
            }
        }

        $detailsTables = $crawler->filter('table.details table.item');
        $details = [];
        foreach ($detailsTables as $detailsTable) {
            /** @var $detailsTable Crawler */
            try {
                $details[$detailsTable->getElementsByTagName('th')->item(0)->textContent] =
                    preg_replace('/\t*|\n*|\s{2,}/u', '', $detailsTable->getElementsByTagName('td')->item(0)->textContent);
            } catch (\Exception $e) {
                array_push($failedFields, $detailsTable->text());
            }
        }
        if ($details) {
            $offer->details = $details;
        }
        if ($failedFields and $failedFields !== ["price_string"]) { // lots of offers don't have assigned price
            $logger->warning('Failed fields', self::arrayInsert($context, 'failed_fields', $failedFields));
        }
        if ($olxTimestamp = self::parseDate($offer->date_string)) {
            $offer->created_at_olx = $olxTimestamp;
        }
        try {
            $offer->save();
        } catch (QueryException $e) {
            if (23000 === intval($e->getCode())) {
                return; // duplicate offer, finish job
            }
            $logger->error('', self::arrayInsert($context, 'exception', $e));
            throw $e;
        }
        if ($offer) {
            try {
                $detectPhones = new DetectPhones($offer);
                $detectPhones->handle();
            } catch (\Exception $e) {
                \Log::critical('Failed to detect phones', ['exception' => $e]);
            }
        }
        if ($offer->wasRecentlyCreated) {
            $photos = [];
            try {
                $photos = $crawler->filter('#bigGallery a')->reduce(function (Crawler $node, $i) {
                    return (boolean)$node->attr('href');
                })->extract('href');
            } catch (Exception $e) {
                $logger->error('Failed to get photos', self::arrayInsert($context, 'exception', $e));
            }
            if ($photos) {
                foreach ($photos as $photo) {
                    $offer->photos()->create(['url' => $photo]);
                }
            }
            if ($locationString = self::getLocationString($crawler)) {
                if ($location = Location::where('location', $locationString)->first()) {
                    $offer->location()->associate($location);
                    $offer->save();
                } else {
                    try {
                        $location = Location::create([
                            'location' => $locationString
                        ]);
                    } catch (QueryException $e) {
                        $logger->error('Unable to create Location', self::arrayInsert($context, 'location', $locationString));
                    }
                    if ($location instanceof Location) {
                        $offer->location()->associate($location);
                        $offer->save();
                    }
                }
            }
        }
        Event::fire(new OfferParsed($offer));
    }

    private static function getLocationString(Crawler $crawler)
    {
        try {
            return trim($crawler->filter('.show-map-link')->first()->text());
        } catch (\Exception $e) {
            return null;
        }
    }


    private static function parseDate($dateString)
    {
        $ruMonths = ['января ', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
        $normalDateString = str_replace($ruMonths, range(1, 12), $dateString);
        try {
            return Carbon::createFromFormat('H:i, j n Y', $normalDateString);
        } catch (InvalidArgumentException $e) {
            return null;
        }
    }

    private static function getCatPath($html)
    {
        if (preg_match('/var cat_path=\'([^\']+)/', $html, $matches)) {
            return $matches[1];
        } else {
            throw new \Exception('cant get cat path');
        }
    }

    private static function getOfferNumber(Crawler $crawler)
    {
        $offerNumberString = $crawler
            ->filter('.offerheadinner > p > small > span')
            ->text();
        if (preg_match('/Номер объявления: (\d+)/u', $offerNumberString, $matches)) {
            return $matches[1];
        } else {
            throw new \ErrorException("invalid offer number string [$offerNumberString]");
        }
    }


    private static function getDate(Crawler $crawler)
    {
        $dateString = $crawler
            ->filter('.offerheadinner > p > small > span')
            ->first()
            ->text();
        if (preg_match('/(\d\d:\d\d, \d\d? \w+ \d{4})/u', $dateString, $matches)) {
            return $matches[1];
        } else {
            throw new \Exception('cant get date');
        }
    }

    private static function getPhones(Client $client, $olxId)
    {
        try {
            $response = $client->get('http://olx.ua/ajax/misc/contact/phone/' . $olxId);
        } catch (ClientException $e) {
            if (404 === intval($e->getCode())) {
                return json_encode([]);
            } else {
                throw $e;
            }
        }
        $data = json_decode($response->getBody()->getContents(), true);
        if (isset($data['value'])) {
            if (strstr($data['value'], '<span')) {
                if (preg_match_all('/>([^<]{6,})</', $data['value'], $matches)) {
                    return json_encode($matches[1]);
                } else {
                    throw new \Exception('cant extract phone');
                }
            } else {
                return json_encode([$data['value']]);
            }
        } else {
            throw new \Exception('no value in number'); // TODO: parser exception class
        }
    }

    private static function getOlxId($url)
    {
        if (preg_match('/ID([^.]+)\./', $url, $matches)) {
            return $matches[1];
        } else {
            throw new \Exception('can\'t get olx offer id'); // TODO: parser exception class
        }
    }

    private static function arrayInsert(array $array, $key, $value)
    {
        $array[$key] = $value;
        return $array;
    }

}
