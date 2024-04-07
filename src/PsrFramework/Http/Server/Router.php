<?php

namespace Csu\PsrFramework\Http\Server;

use Csu\PsrFramework\Di\ComponentContainer;
use Csu\PsrFramework\Exceptions\ContainerException;
use Csu\PsrFramework\Exceptions\RouteNotFoundException;
use Csu\PsrFramework\Http\Server\Attributes\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;

class Router implements RouteInterface
{
    private array $routes = [];

    public function __construct(private readonly ComponentContainer $container)
    {

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

    /**
     * @throws ReflectionException
     * @throws RouteNotFoundException
     * @throws ContainerException
     */
    public function resolve(string $requestUri, string $requestMethod)
    {
        $route = explode("?", $requestUri)[0];
        $action = $this->routes[$requestMethod][$route] ?? null;

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

    public function add($middleware): self
    {
        // TODO
        return $this;
    }

    public function addMiddleware(MiddlewareInterface $middleware): self
    {
        // TODO
        return $this;
    }

    public function addErrorMiddleware()
    {

    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // TODO: Implement handle() method.
    }
}
