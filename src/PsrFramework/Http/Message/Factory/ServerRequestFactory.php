<?php

namespace Csu\PsrFramework\Http\Message\Factory;

use Csu\PsrFramework\Http\Message\ServerRequest;
use InvalidArgumentException;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

class ServerRequestFactory implements ServerRequestFactoryInterface
{

    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        if (!$uri instanceof UriInterface) {
            throw new InvalidArgumentException('Invalid URI provided');
        }

        $serverRequest = new ServerRequest(
            $uri,
            $serverParams,
            [], // cookieParams
            [], //$uri->getQueryParams(), // queryParams from URI
            null, // body
            [], // uploadedFiles
            [], // parsedBody
            [], // attributes
        );

        return $serverRequest;
    }
}
