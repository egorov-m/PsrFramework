<?php

namespace Csu\PsrFramework\Server;

use Csu\PsrFramework\Server\Exceptions\HeadersAlreadySentException;
use Csu\PsrFramework\Server\Exceptions\OutputAlreadySentException;
use Psr\Http\Message\ResponseInterface;

class Emitter implements EmitterInterface
{
    /**
     * @throws HeadersAlreadySentException
     * @throws OutputAlreadySentException
     */
    public function emit(ResponseInterface $response, bool $withoutBody = false): void
    {
        if (headers_sent()) {
            throw new HeadersAlreadySentException();
        }

        if (ob_get_level() > 0 && ob_get_length() > 0) {
            throw new OutputAlreadySentException();
        }

        $this->emitHeaders($response);
        $this->emitStatusLine($response);

        if (!$withoutBody && $response->getBody()->isReadable()) {
            $this->emitBody($response);
        }
    }

    private function emitHeaders(ResponseInterface $response): void
    {
        foreach ($response->getHeaders() as $name => $value) {
            $name = str_replace(" ", "-", ucwords(strtolower(str_replace("-", " ", (string) $name))));
            $firstReplace = !($name === "Set-Cookie");

            header("$name: $value", $firstReplace);
        }
    }

    private function emitStatusLine(ResponseInterface $response): void
    {
        $statusCode = $response->getStatusCode();
        $reasonPhrase = trim($response->getReasonPhrase());
        $protocolVersion = trim($response->getProtocolVersion());

        $status = $statusCode . (!$reasonPhrase ? "" : " $reasonPhrase");
        header("HTTP/$protocolVersion $status", true, $statusCode);
    }

    private function emitBody(ResponseInterface $response): void
    {
        echo $response->getBody();
    }
}
