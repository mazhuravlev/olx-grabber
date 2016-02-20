<?php

namespace App\Console;

use App\Console\Commands\ParseDetailsParameters;
use App\Jobs\Parse;
use App\Models\GrabbedUrl;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Log;
use Symfony\Component\DomCrawler\Crawler;

class Kernel extends ConsoleKernel
{

    use DispatchesJobs;

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ParseDetailsParameters::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $client = new Client();
            $baseUrl = 'http://olx.ua/nedvizhimost/cri/?page=%d';
            $page = 1;
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
                foreach ($urls as $url) {
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
                        $this->dispatch(new Parse($grabbedUrl));
                        $grabbedCount++;
                    }
                }
                printf('[%d] grabbed: %d, dropped: %d' . PHP_EOL, time() - $time, $grabbedCount, $droppedCount);
            } while ($grabbedCount > 0);
        })
            ->name('grab_links')
            ->withoutOverlapping()
            ->everyMinute();
    }
}
