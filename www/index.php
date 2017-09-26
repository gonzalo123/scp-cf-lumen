<?php

use App\Http\Middleware;
use Laravel\Lumen\Application;
use Laravel\Lumen\Routing\Router;
use Predis\Client;

if (php_sapi_name() == "cli-server") {
    require __DIR__ . '/../vendor/autoload.php';
    $env = 'dev';
} else {
    require 'vendor/autoload.php';
    $env = 'prod';
}

(new Dotenv\Dotenv(__DIR__ . "/../env/{$env}"))->load();

$app = new Application();

$app->routeMiddleware([
    'auth' => Middleware\AuthMiddleware::class,
]);

$app->register(App\Providers\VcapServiceProvider::class);
$app->register(App\Providers\StdoutLogServiceProvider::class);
$app->register(App\Providers\DbServiceProvider::class);
$app->register(App\Providers\RedisServiceProvider::class);

$router = $app->router;

$router->group(['middleware' => 'auth'], function (Router $router) {
    $router->get("/", function () {
        return view("index", [
            'user' => config("user"),
            'ttl'  => getenv('TTL'),
        ]);
    });

    $router->get("/timestamp", function (Client $redis, PDO $conn) {
        if (!$redis->exists('timestamp')) {
            $stmt = $conn->prepare('SELECT localtimestamp');
            $stmt->execute();
            $redis->set('timestamp', $stmt->fetch()['TIMESTAMP'], 'EX', getenv('TTL'));
        }

        return response()->json($redis->get('timestamp'));
    });
});

$app->run();
