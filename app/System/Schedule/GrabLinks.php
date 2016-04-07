<?php

namespace App\System\Schedule;


use App\Jobs\Parse;
use App\Models\GrabbedUrl;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Bus\Dispatcher;
use Log;
use Symfony\Component\DomCrawler\Crawler;

class GrabLinks
{

    public static function grabLinks()
    {
        $client = new Client();
        $baseUrl = 'http://olx.ua/nedvizhimost/cri/?page=%d';
        $page = 1;
        $retryCount = 5;
        do {
            $time = time();
            $response = null;
            try {
                $response = $client->get(sprintf($baseUrl, $page++));
            } catch (ServerException $e) {
                Log::error('OLX server error', ['code' => $e->getCode(), 'message' => $e->getMessage()]);
                return;
            }
            $urls = [];
            if ($response) {
                $crawler = new Crawler($response->getBody()->getContents());
                $urls = array_map(
                    function ($link) {
                        return preg_replace('/#.*$/', '', $link);
                    },
                    $crawler->filter('td.offer a.link')->extract('href')
                );
            }
            if (0 === count($urls)) {
                Log::info('Grabbed 0 OLX links');
            }
            $grabbedCount = 0;
            $droppedCount = 0;
            /** @var Dispatcher $dispatcher */
            $dispatcher = app('Illuminate\Bus\Dispatcher');
            foreach ($urls as $url) {
                /** @var GrabbedUrl $grabbedUrl */
                $grabbedUrl = GrabbedUrl::where('url', $url)->first();
                if ($grabbedUrl) {
                    /** @var GrabbedUrl $grabbedUrl */
                    $grabbedUrl->count = $grabbedUrl->count + 1;
                    $grabbedUrl->save();
                    $droppedCount++;
                } else {
                    $grabbedUrl = GrabbedUrl::create(
                        [
                            'url' => $url
                        ]
                    );
                    $dispatcher->dispatch(new Parse($grabbedUrl));
                    $grabbedCount++;
                }
            }
            printf('[%d] grabbed: %d, dropped: %d' . PHP_EOL, time() - $time, $grabbedCount, $droppedCount);
        } while ($grabbedCount > 0 or $retryCount-- > 0);
    }

}