<?php

namespace Csu\PsrFramework\Http\Server\Middleware;

use Csu\PsrFramework\Http\Message\Response;
use HttpException;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ErrorMiddleware implements MiddlewareInterface
{
    protected bool $displayErrorDetails;
    protected bool $logErrors;
    protected bool $logErrorDetails;
    protected array $handlers = [];
    protected array $subClassHandlers = [];
    protected $defaultErrorHandler;

    public function __construct(
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ) {
        $this->displayErrorDetails = $displayErrorDetails;
        $this->logErrors = $logErrors;
        $this->logErrorDetails = $logErrorDetails;
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
        if ($exception instanceof HttpException) {
            $request = $exception->getRequest();
        }

        $exceptionType = get_class($exception);
        $handler = $this->getErrorHandler($exceptionType);

        return $handler($request, $exception, $this->displayErrorDetails, $this->logErrors, $this->logErrorDetails);
    }

    public function getErrorHandler(string $type)
    {
        return $this->handlers[$type] ?? $this->defaultErrorHandler;
    }

    public function setErrorHandler($typeOrTypes, $handler, bool $handleSubclasses = false): self
    {
        $isTypeArray = is_array($typeOrTypes);
        $types = $isTypeArray ? $typeOrTypes : [$typeOrTypes];

        foreach ($types as $type) {
            $this->addErrorHandler($type, $handler, $handleSubclasses);
        }

        return $this;
    }

    private function addErrorHandler(string $type, $handler, bool $handleSubclasses): void
    {
        if ($handleSubclasses) {
            $this->subClassHandlers[$type] = $handler;
        } else {
            $this->handlers[$type] = $handler;
        }
    }

    public function setDefaultErrorHandler($handler): self
    {
        $this->defaultErrorHandler = $handler;
        return $this;
    }

}
