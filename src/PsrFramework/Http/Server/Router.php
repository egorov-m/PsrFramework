<?php

namespace Csu\PsrFramework\Http\Server;

use Csu\PsrFramework\Di\ComponentContainer;
use Csu\PsrFramework\Enums\HttpStatusCode;
use Csu\PsrFramework\Exceptions\ContainerException;
use Csu\PsrFramework\Exceptions\RouteNotFoundException;
use Csu\PsrFramework\Http\Server\Attributes\Route;
use Jenssegers\Blade\Blade;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;

class Router implements RouteInterface
{
    private array $routes = [];

    protected MiddlewareDispatcher $middlewareDispatcher;

    public function __construct(
        private readonly ComponentContainer $container,
        private readonly ServerRequestFactoryInterface $serverRequestFactory,
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly EmitterInterface $emitter = new Emitter()
    )
    {
        $this->middlewareDispatcher = new MiddlewareDispatcher($this, $this->container);
    }

    /**
     * @throws ReflectionException
     */
    public function registerRouteFromControllerAttributes(array $controllers): void
    {
        foreach ($controllers as $controller) {
            $reflectionController = new ReflectionClass($controller);

            foreach ($reflectionController->getMethods() as $method) {
                $attributes = $method->getAttributes(Route::class, ReflectionAttribute::IS_INSTANCEOF);

                foreach ($attributes as $attribute) {
                    $route = $attribute->newInstance();

                    $this->register($route->method->value, $route->routePath, [$controller => $method->getName()]);
                }
            }
        }
    }

    public function register(string $requestMethod, string $router, callable|array $action): self
    {
        $this->routes[$requestMethod][$router] = $action;

        return $this;
    }

    public function routes(): array
    {
        return $this->routes;
    }

    public function addMiddleware(MiddlewareInterface $middleware): self
    {
        $this->middlewareDispatcher->addMiddleware($middleware);
        return $this;
    }

    public function run(array $serverParams = []): void
    {
        $request = $this->serverRequestFactory->createServerRequest(
            $_SERVER["REQUEST_METHOD"],
            $_SERVER["REQUEST_URI"],
            $serverParams
        );

        try {
            $response = $this->middlewareDispatcher->handle($request);
            $this->emitter->emit($response);

        } catch (RouteNotFoundException) {
            $response = $this->responseFactory->createResponse(HttpStatusCode::StatusNotFound->value);
            $body = $response->getBody();
            $body->write(
                $this->container->get(Blade::class)->render("error/404")
            );
            $response->withHeader("Content-Type", "text/html");
            $this->emitter->emit($response);
        }
    }

    /**
     * @throws ReflectionException
     * @throws RouteNotFoundException
     * @throws ContainerException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $route = explode("?", $request->getUri()->getPath())[0];
        $action = $this->routes[$request->getMethod()][$route] ?? null;

        if (! $action) {
            throw new RouteNotFoundException();
        }

        if (is_callable($action)) {
            return call_user_func($action);
        }

        $keys = array_keys($action);
        $values = array_values($action);
        $class = $keys[0];
        $method = $values[0];

        if (class_exists($class)) {
            $class = $this->container->get($class);

            if (method_exists($class, $method)) {
                return call_user_func_array([$class, $method], []);
            }
        }

        throw new RouteNotFoundException();
    }
}
