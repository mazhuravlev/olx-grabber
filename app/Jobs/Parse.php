<?php

namespace App\Jobs;

use App\Models\GrabbedUrl;
use App\Models\Offer;
use App\System\ParserLoggerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\DomCrawler\Crawler;

class Parse extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

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
        $logger->info('grab', $context);
        try {
            $response = $client->get($this->url->url);
        } catch (ClientException $e) {
            switch ($code = $e->getCode()) {
                case 404:
                    $logger->warning(
                        'Offer not found',
                        $context
                    );
                    return;
                default:
                    throw new \Exception($code); // TODO: what codes can be present here?
            }
        } catch (\Exception $e) {
            throw $e;
        }
        $responseHtml = $response->getBody()->getContents();
        $crawler = new Crawler($responseHtml);
        $offer = new Offer();
        $offer->price_string = trim($crawler->filter('.pricelabel.tcenter')->first()->text());
        $offer->title = trim($crawler->filter('.offerheadinner > h1')->text());
        $offer->phone = self::getPhones($client, self::getOlxId($this->url->url));
        $offer->olx_id = self::getOlxId($this->url->url);
        $offer->href = $this->url->url;
        $offer->description = trim($crawler->filter('#textContent')->text());
        $offer->location = trim($crawler->filter('.show-map-link')->first()->text());
        $offer->date_string = self::getDate($crawler);
        $offer->offer_number = self::getOfferNumber($crawler);
        $offer->cat_path = self::getCatPath($responseHtml);
        $offer->save();
    }

    private static function getCatPath($html)
    {
        if(preg_match('/var cat_path=\'([^\']+)/', $html, $matches)) {
            return $matches[1];
        } else {
            throw new \Exception('cant get cat path');
        }
    }

    private
    static function getOfferNumber(Crawler $crawler)
    {
        $offerNumberString = $crawler
            ->filter('.offerheadinner > p > small > span > span')
            ->first()
            ->text();
        return preg_replace('/\D/', '', $offerNumberString);
    }


    private
    static function getDate(Crawler $crawler)
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

    private
    static function getPhones(Client $client, $olxId)
    {
        $response = $client->get('http://olx.ua/ajax/misc/contact/phone/' . $olxId);
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

    private
    static function getOlxId($url)
    {
        if (preg_match('/ID([^.]+)\./', $url, $matches)) {
            return $matches[1];
        } else {
            throw new \Exception('can\'t get olx offer id'); // TODO: parser exception class
        }
    }

}
