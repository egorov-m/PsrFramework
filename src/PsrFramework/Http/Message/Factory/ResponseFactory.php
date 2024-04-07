<?php

namespace Csu\PsrFramework\Http\Message\Factory;

use Csu\PsrFramework\Http\Message\Response;
use InvalidArgumentException;
use \Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class ResponseFactory implements ResponseFactoryInterface
{

    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        $serverProtocol = $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.1';

        $protocol = substr($serverProtocol, strpos($serverProtocol, '/') + 1);

        if (!in_array((string) $protocol, ['1.0', '1.1'])) {
            throw new InvalidArgumentException('Unsupported HTTP protocol version number. "' . $protocol . '" provided.');
        }


        $streamFactory = new StreamFactory();

        $body = $streamFactory->createStream();

        return new Response(
            $code,
            [],
            $body,
            $protocol,
            $reasonPhrase
        );
    }
}
