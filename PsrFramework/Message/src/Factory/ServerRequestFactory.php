<?php

namespace Csu\PsrFramework\Message\Factory;

use Csu\PsrFramework\Message\ServerRequest;
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

        $params = [];
        parse_str($uri->getQuery(), $params);

        $serverRequest = new ServerRequest(
            $uri,
            $method,
            $serverParams,
            [], // cookieParams
            $params, // queryParams from URI
            fopen("php://input", "r+"), // body
            getallheaders(), // headers
            [], // uploadedFiles
            [], // parsedBody
            [], // attributes
        );

        return $serverRequest;
    }
}
