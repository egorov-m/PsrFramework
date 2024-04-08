<?php

namespace Csu\PsrFramework\Examples\Controllers;
use Csu\PsrFramework\Enums\HttpStatusCode;
use Csu\PsrFramework\Http\Message\Factory\ResponseFactory;
use Csu\PsrFramework\Http\Message\Response;
use Csu\PsrFramework\Http\Server\Attributes\Get;
use Jenssegers\Blade\Blade;
use Psr\Http\Message\ServerRequestInterface;

readonly class ProtectedController
{
    public function __construct(private Blade $blade, private ResponseFactory $responseFactory)
    {

    }
    #[Get("/protected")]
    public  function index(ServerRequestInterface $request): Response
    {
        $response = $this->responseFactory->createResponse(HttpStatusCode::StatusOk->value);
        $body = $response->getBody();
        $body->write(
            $this->blade->render("protected", ["title" => "This page is protected"])
        );
        return $response->withHeader("Content-Type", "text/html");
    }
}