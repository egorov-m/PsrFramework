<?php

namespace Csu\PsrFramework;

use Csu\PsrFramework\Di\ComponentContainer;
use Csu\PsrFramework\Examples\Controllers\HomeController;
use Csu\PsrFramework\Http\Server\Router;
use Jenssegers\Blade\Blade;

require dirname(__DIR__) . "/vendor/autoload.php";

define("VIEW_PATH", dirname(__DIR__) .  "/examples/views");
define("CACHE_PATH", dirname(__DIR__) .  "/examples/cache");

$container = new ComponentContainer([]);
$container->set(Blade::class, fn() => new Blade(VIEW_PATH, CACHE_PATH));

$router = new Router($container);

$router->registerRouteFromControllerAttributes(
    [
        HomeController::class,
    ]
);

(new App(
    $container,
    $router,
    ["uri" => $_SERVER["REQUEST_URI"], "method" => $_SERVER["REQUEST_METHOD"]],
    []
))->run();
