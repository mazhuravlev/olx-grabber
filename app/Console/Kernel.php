<?php

namespace App\Console;

use App\Console\Commands\ExportCommand;
use App\Console\Commands\ParseDetailsParameters;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ParseDetailsParameters::class,
        ExportCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call('App\System\Schedule\GrabLinks::grabLinks')
            ->name('grab_links')
            ->withoutOverlapping()
            ->everyMinute();
    }
}
