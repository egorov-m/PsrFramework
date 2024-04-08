<?php

namespace Csu\PsrFramework\Server;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareDispatcher implements MiddlewareDispatcherInterface
{
    protected RequestHandlerInterface $kernel;
    protected ?ContainerInterface $container;

    public function __construct(
        RequestHandlerInterface $kernel,
        ?ContainerInterface $container = null
    ) {
        $this->kernel = $kernel;
        $this->container = $container;
    }

    public function addMiddleware(MiddlewareInterface $middleware): MiddlewareDispatcherInterface
    {
        $next = $this->kernel;
        $this->kernel = new class ($middleware, $next) implements RequestHandlerInterface {
            private MiddlewareInterface $middleware;
            private RequestHandlerInterface $next;

            public function __construct(MiddlewareInterface $middleware, RequestHandlerInterface $next)
            {
                $this->middleware = $middleware;
                $this->next = $next;
            }

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return $this->middleware->process($request, $this->next);
            }
        };

        return $this;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->kernel->handle($request);
    }
}
