<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use PDO;

class DbServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $vcapServices = app('vcap_services');

        $dbConf = $vcapServices['postgresql'][0]['credentials'];
        $dsn    = "pgsql:dbname={$dbConf['dbname']};host={$dbConf['hostname']};port={$dbConf['port']}";
        $dbh    = new PDO($dsn, $dbConf['username'], $dbConf['password']);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_UPPER);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $this->app->bind(PDO::class, function ($app) use ($dbh) {
            return $dbh;
        });
    }
}
