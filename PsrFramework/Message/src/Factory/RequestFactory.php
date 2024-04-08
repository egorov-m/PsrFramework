<?php

namespace Csu\PsrFramework\Message\Factory;

use Csu\PsrFramework\Message\Request;
use InvalidArgumentException;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class RequestFactory implements RequestFactoryInterface
{
    public function createRequest(string $method, $uri): RequestInterface
    {
        if (!$uri instanceof UriInterface) {
            throw new InvalidArgumentException('Invalid URI provided');
        }

        $request = new Request($uri);
        $request = $request->withMethod($method);

        return $request;
    }
}
