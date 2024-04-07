<?php

namespace Csu\PsrFramework\Http\Message\Factory;

use Csu\PsrFramework\Http\Message\ServerRequest;
use Csu\PsrFramework\Http\Message\Stream;
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
            $method,
            $serverParams,
            [], // cookieParams
            [], //$uri->getQueryParams(), // queryParams from URI
            fopen("php://input", "r+"), // body
            getallheaders(), // headers
            [], // uploadedFiles
            [], // parsedBody
            [], // attributes
        );

        return $serverRequest;
    }
}
