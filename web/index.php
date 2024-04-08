<?php

namespace Csu\PsrFramework;

use Csu\PsrFramework\Di\ComponentContainer;
use Csu\PsrFramework\Examples\Controllers\AuthController;
use Csu\PsrFramework\Examples\Controllers\HomeController;
use Csu\PsrFramework\Http\Message\Factory\ResponseFactory;
use Csu\PsrFramework\Http\Message\Factory\ServerRequestFactory;
use Csu\PsrFramework\Http\Server\Middleware\BodyParsingMiddleware;
use Csu\PsrFramework\Http\Server\Router;
use Jenssegers\Blade\Blade;

require dirname(__DIR__) . "/vendor/autoload.php";

define("VIEW_PATH", dirname(__DIR__) .  "/examples/views");
define("CACHE_PATH", dirname(__DIR__) .  "/examples/cache");

$container = new ComponentContainer([]);
$container->set(Blade::class, fn() => new Blade(VIEW_PATH, CACHE_PATH));

$router = new Router($container, new ServerRequestFactory(), new ResponseFactory());
$router->addMiddleware(new BodyParsingMiddleware());

$router->registerRouteFromControllerAttributes(
    [
        HomeController::class,
        AuthController::class,
    ]
);

(new App(
    $container,
    $router,
    []
))->run();
