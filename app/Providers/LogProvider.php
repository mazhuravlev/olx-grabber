<?php

namespace App\Providers;

use App\System\ParserLogger;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\ServiceProvider;
use Monolog\Handler\StreamHandler;

class LogProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\System\ParserLoggerInterface', function () {
            $logger = new ParserLogger('parser');
            $logger->pushHandler(
                new StreamHandler(storage_path('logs/parser.log'))
            );
            return $logger;
        });
    }
}
