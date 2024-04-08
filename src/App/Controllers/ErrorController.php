<?php

namespace Csu\App\Controllers;

use Csu\PsrFramework\Message\Factory\ResponseFactory;
use Csu\PsrFramework\Message\Response;
use Csu\PsrFramework\Server\Enums\HttpStatusCode;
use Csu\PsrFramework\Server\Attributes\Get;
use Csu\PsrFramework\Server\Exceptions\ForbiddenException;
use Csu\PsrFramework\Server\Exceptions\InternalServerException;
use Csu\PsrFramework\Server\Exceptions\NotFoundException;
use Csu\PsrFramework\Server\Exceptions\UnauthorizedException;
use Jenssegers\Blade\Blade;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

readonly class ErrorController
{
    public function __construct(private Blade $blade, private ResponseFactory $responseFactory)
    {
    }

    /**
     * @throws NotFoundException
     * @throws ForbiddenException
     * @throws UnauthorizedException
     * @throws InternalServerException
     */
    #[Get("/error")]
    public function index(ServerRequestInterface $request): Response
    {
        $params = $request->getQueryParams();
        $errorCode = "";
        if (array_key_exists("error_code", $params)) {
            $errorCode = (string) $params["error_code"];
        }
        throw match ($errorCode) {
            "404" => new NotFoundException(),
            "403" => new ForbiddenException(),
            "401" => new UnauthorizedException(),
            default => new InternalServerException(),
        };
    }

    public function handle404Error(ServerRequestInterface $request, Throwable $exception): Response
    {
        $response = $this->responseFactory->createResponse(HttpStatusCode::StatusNotFound->value);
        $body = $response->getBody();
        $body->write(
            $this->blade->render(
                "error",
                [
                    "statusCode" => HttpStatusCode::StatusNotFound->value,
                    "description" => "Not found"
                ]
            )
        );
        return $response;
    }

    public function handle403Error(ServerRequestInterface $request, Throwable $exception): Response
    {
        $response = $this->responseFactory->createResponse(HttpStatusCode::StatusForbidden->value);
        $body = $response->getBody();
        $body->write(
            $this->blade->render(
                "error",
                [
                    "statusCode" => HttpStatusCode::StatusForbidden->value,
                    "description" => "Forbidden error"
                ]
            )
        );
        return $response;
    }

    public function handle401Error(ServerRequestInterface $request, Throwable $exception): Response
    {
        $response = $this->responseFactory->createResponse(HttpStatusCode::StatusUnauthorized->value);
        $body = $response->getBody();
        $body->write(
            $this->blade->render(
                "error",
                [
                    "statusCode" => HttpStatusCode::StatusUnauthorized->value,
                    "description" => "Unauthorized error"
                ]
            )
        );
        return $response;
    }

    public function handle500Error(ServerRequestInterface $request, Throwable $exception): Response
    {
        $response = $this->responseFactory->createResponse(HttpStatusCode::StatusInternalServerError->value);
        $body = $response->getBody();
        $body->write(
            $this->blade->render(
                "error",
                [
                    "statusCode" => HttpStatusCode::StatusInternalServerError->value,
                    "description" => "Internal Server Error"
                ]
            )
        );
        return $response;
    }
}
