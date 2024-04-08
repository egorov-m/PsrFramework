<?php

namespace Csu\PsrFramework;

use Csu\PsrFramework\Di\ComponentContainer;
use Csu\PsrFramework\Examples\Controllers\ErrorController;
use Csu\PsrFramework\Examples\Controllers\AuthController;
use Csu\PsrFramework\Examples\Controllers\HomeController;
use Csu\PsrFramework\Examples\Controllers\ProtectedController;
use Csu\PsrFramework\Examples\middleware\AuthMiddleware;
use Csu\PsrFramework\Exceptions\ForbiddenException;
use Csu\PsrFramework\Exceptions\InternalServerException;
use Csu\PsrFramework\Exceptions\NotFoundException;
use Csu\PsrFramework\Exceptions\UnauthorizedException;
use Csu\PsrFramework\Http\Message\Factory\ResponseFactory;
use Csu\PsrFramework\Http\Message\Factory\ServerRequestFactory;
use Csu\PsrFramework\Http\Server\Middleware\BodyParsingMiddleware;
use Csu\PsrFramework\Http\Server\Middleware\ErrorMiddleware;
use Csu\PsrFramework\Http\Server\Router;
use Jenssegers\Blade\Blade;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

require dirname(__DIR__) . "/vendor/autoload.php";

define("VIEW_PATH", dirname(__DIR__) .  "/examples/views");
define("CACHE_PATH", dirname(__DIR__) .  "/examples/cache");

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
