<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Monolog;

class StdoutLogServiceProvider extends ServiceProvider
{
    public function register()
    {
        app()->configureMonologUsing(function (Monolog\Logger $monolog) {
            return $monolog->pushHandler(new \Monolog\Handler\ErrorLogHandler());
        });
    }
}