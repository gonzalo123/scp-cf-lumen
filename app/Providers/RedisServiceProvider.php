<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Predis\Client;

class RedisServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $vcapServices = app('vcap_services');
        $redisConf    = $vcapServices['redis'][0]['credentials'];

        $redis = new Client([
            'scheme'   => 'tcp',
            'host'     => $redisConf['hostname'],
            'port'     => $redisConf['port'],
            'password' => $redisConf['password'],
        ]);

        $this->app->bind(Client::class, function ($app) use ($redis) {
            return $redis;
        });
    }
}
