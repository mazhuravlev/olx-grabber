<?php

namespace App\Console;

use App\Jobs\Parse;
use App\Models\GrabbedUrl;
use DB;
use GuzzleHttp\Client;
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
        // Commands\Inspire::class,
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
                $response = $client->get(sprintf($baseUrl, $page++));
                $crawler = new Crawler($response->getBody()->getContents());
                $urls = array_map(
                    function ($link) {
                        return preg_replace('/#.*$/', '', $link);
                    },
                    $crawler->filter('td.offer a.link')->extract('href')
                );
                assert(44 === count($urls));
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
                        $this->dispatch(
                            new Parse(
                                GrabbedUrl::create(
                                    [
                                        'url' => $url
                                    ]
                                )
                            )
                        );
                        $grabbedCount++;
                    }
                }
                printf('[%d] grabbed: %d, dropped: %d' . PHP_EOL, time() - $time, $grabbedCount, $droppedCount);
            } while($grabbedCount > 0);
        })
            ->name('grab_links')
            ->withoutOverlapping()
            ->everyMinute();
    }
}
