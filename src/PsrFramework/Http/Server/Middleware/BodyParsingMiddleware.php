<?php

namespace Csu\PsrFramework\Http\Server\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

class BodyParsingMiddleware implements MiddlewareInterface
{
    protected array $bodyParsers;

    public function __construct(array $bodyParsers = [])
    {
        $this->registerDefaultBodyParsers();
        foreach ($bodyParsers as $contentType => $parser) {
            $this->registerBodyParser($contentType, $parser);
        }
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $parsedBody = $request->getParsedBody();

        if (empty($parsedBody)) {
            $parsedBody = $this->parseBody($request);
            if ($parsedBody != null) {
                $request = $request->withParsedBody($parsedBody);
            }
        }

        return $handler->handle($request);
    }

    public function registerDefaultBodyParsers(): void
    {
        $this->registerBodyParser("application/json", static function ($body) {
            return json_decode($body, true);
        });
        $this->registerBodyParser("application/x-www-form-urlencoded", static function ($body) {
            parse_str($body, $parsedArray);
            return $parsedArray;
        });
    }

    public function registerBodyParser(string $contentType, callable $callable): self
    {
        $this->bodyParsers[$contentType] = $callable;
        return $this;
    }

    public function parseBody(ServerRequestInterface $request)
    {
        $contentType = $request->getHeaderLine("content-type");

        if (isset($this->bodyParsers[$contentType])) {
            $body = (string)$request->getBody();
            $parsed = $this->bodyParsers[$contentType]($body);

            if ($parsed !== null && !is_object($parsed) && !is_array($parsed)) {
                throw new RuntimeException(
                    "Request body content type parser return value must be an array, an object, or null"
                );
            }

            return $parsed;
        }

        return null;
    }
}
