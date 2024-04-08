<?php

namespace Csu\App;

require dirname(__DIR__) . "/vendor/autoload.php";;

use Csu\App\Controllers\AuthController;
use Csu\App\Controllers\ErrorController;
use Csu\App\Controllers\HomeController;
use Csu\App\Controllers\ProtectedController;
use Csu\App\Middleware\AuthMiddleware;
use Csu\PsrFramework\Di\Components\ComponentContainer;
use Csu\PsrFramework\Message\Factory\ResponseFactory;
use Csu\PsrFramework\Message\Factory\ServerRequestFactory;
use Csu\PsrFramework\Server\Exceptions\ForbiddenException;
use Csu\PsrFramework\Server\Exceptions\InternalServerException;
use Csu\PsrFramework\Server\Exceptions\NotFoundException;
use Csu\PsrFramework\Server\Exceptions\UnauthorizedException;
use Csu\PsrFramework\Server\Middleware\BodyParsingMiddleware;
use Csu\PsrFramework\Server\Middleware\ErrorMiddleware;
use Csu\PsrFramework\Server\Router;
use Dotenv\Dotenv;
use Jenssegers\Blade\Blade;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

define("VIEW_PATH", dirname(__DIR__) . "/src/App/views");
define("CACHE_PATH", dirname(__DIR__) . "/src/App/cache");

// load config from .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$container = new ComponentContainer([]);
$container->set(Blade::class, fn() => new Blade(VIEW_PATH, CACHE_PATH));

$router = new Router($container, new ServerRequestFactory(), new ResponseFactory());
$router->addMiddleware(new BodyParsingMiddleware());

$errorController = new ErrorController(new Blade(VIEW_PATH, CACHE_PATH), new ResponseFactory());
$router->addMiddleware(new AuthMiddleware(["/protected"]));
$router->addMiddleware(new ErrorMiddleware([
    NotFoundException::class =>
        fn(ServerRequestInterface $request, Throwable $exception) =>
        $errorController->handle404Error($request, $exception),
    ForbiddenException::class =>
        fn(ServerRequestInterface $request, Throwable $exception) =>
        $errorController->handle403Error($request, $exception),
    UnauthorizedException::class =>
        fn(ServerRequestInterface $request, Throwable $exception) =>
        $errorController->handle401Error($request, $exception),
    InternalServerException::class =>
        fn(ServerRequestInterface $request, Throwable $exception) =>
        $errorController->handle500Error($request, $exception)
]));

$router->registerRouteFromControllerAttributes(
    [
        HomeController::class,
        AuthController::class,
        ProtectedController::class,
        ErrorController::class
    ]
);
session_start();
(new App(
    $container,
    $router,
    []
))->run();
