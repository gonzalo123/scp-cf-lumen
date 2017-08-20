PHP (Lumen) application in SAP Cloud Platform. With PostgreSQL, Redis and Cloud Foundry
======

I normally use Silex when I need a API server in PHP. I've created an small example of using Silex with PostgreSQL, Redis and Cloud Foundry. Just for test SAP's Cloud Platform and Cloud Foundry as a paas provider. I'm very confortable with Silex. It covers all my needs but there's a problem: Silex is dead. I feel a litle bit sad but I'm not going to cry. It's just a tool and there're another ones. I'm studying another micro frameworks and now it's the turn of Lumen

The idea is create the same application with Lumen instead of Silex. It's a dummy application but it cover task that I normally use. I also will re-use the Redis and PostgreSQL services from the previous project.


```php
use App\Http\Middleware;
use Laravel\Lumen\Application;
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

$app->group(['middleware' => 'auth'], function (Application $app) {
    $app->get("/", function () {
        return view("index", [
            'user' => config("user"),
            'ttl'  => getenv('TTL'),
        ]);
    });

    $app->get("/timestamp", function (Client $redis, PDO $conn) {
        if (!$redis->exists('timestamp')) {
            $stmt = $conn->prepare('SELECT localtimestamp');
            $stmt->execute();
            $redis->set('timestamp', $stmt->fetch()['TIMESTAMP'], 'EX', getenv('TTL'));
        }

        return response()->json($redis->get('timestamp'));
    });
});

$app->run();
```

In summary: Lumen is cool. The interface is very similar to Silex. I can swap my mind from thinking in Silex to thinking in Lumen easily. Blade instead Twig: no problem. Service provider are very similar. Routing is almost the same. Middlewares are much better