<?php

namespace Csu\PsrFramework\Server\Middleware;

use Closure;
use Csu\PsrFramework\Message\Factory\ResponseFactory;
use Csu\PsrFramework\Server\Enums\HttpStatusCode;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class ErrorMiddleware implements MiddlewareInterface
{
    protected Closure $defaultErrorHandler;

    public function __construct(
        protected array $handlers = [],
        protected ResponseFactory $responseFactory = new ResponseFactory()
    ) {
        $this->defaultErrorHandler =
            function (ServerRequestInterface $request, Throwable $exception) {
                return $this->responseFactory->createResponse(
                    HttpStatusCode::StatusInternalServerError->value,
                    "Internal Server Error"
                );
            };
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $e) {
            return $this->handleException($request, $e);
        }
    }

    public function handleException(ServerRequestInterface $request, Throwable $exception): ResponseInterface
    {
        $exceptionType = get_class($exception);
        $handler = $this->getErrorHandler($exceptionType);

        return $handler($request, $exception);
    }

    public function getErrorHandler(string $type)
    {
        return $this->handlers[$type] ?? $this->defaultErrorHandler;
    }

    public function setErrorHandler($typeOrTypes, $handler): self
    {
        $isTypeArray = is_array($typeOrTypes);
        $types = $isTypeArray ? $typeOrTypes : [$typeOrTypes];

        foreach ($types as $type) {
            $this->addErrorHandler($type, $handler);
        }

        return $this;
    }

    private function addErrorHandler(string $type, $handler): void
    {
        $this->handlers[$type] = $handler;
    }

    public function setDefaultErrorHandlers($handler): self
    {
        $this->defaultErrorHandler = $handler;
        return $this;
    }
}
