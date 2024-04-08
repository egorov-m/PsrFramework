<?php

namespace Csu\PsrFramework\Server;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface MiddlewareDispatcherInterface extends RequestHandlerInterface
{
    public function addMiddleware(MiddlewareInterface $middleware): self;
}