<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class VcapServiceProvider extends ServiceProvider
{
    public function register()
    {
        if (php_sapi_name() == "cli-server") {
            $env = file_get_contents(__DIR__ . "/../../conf/vcap_services.json");
        } else {
            $env = $_ENV["VCAP_SERVICES"];
        }

        $vcapServices = json_decode($env, true);

        $this->app->bind('vcap_services', function ($app) use ($vcapServices) {
            return $vcapServices;
        });
    }
}
