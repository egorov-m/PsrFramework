<?php

namespace Csu\PsrFramework\Examples\Controllers;

use Csu\PsrFramework\Enums\HttpStatusCode;
use Csu\PsrFramework\Http\Message\Factory\ResponseFactory;
use Csu\PsrFramework\Http\Message\Response;
use Csu\PsrFramework\Http\Server\Attributes\Post;
use Jenssegers\Blade\Blade;

use Csu\PsrFramework\Http\Server\Attributes\Get;
use Psr\Http\Message\ServerRequestInterface;

readonly class HomeController
{
    public function __construct(private Blade $blade, private ResponseFactory $responseFactory)
    {

    }

    #[Get("/")]
    #[Get("/home")]
    public function index(ServerRequestInterface $request): Response
    {

        $response = $this->responseFactory->createResponse(HttpStatusCode::StatusOk->value);
        $body = $response->getBody();
        $body->write(
            $this->blade->render("index", ["title" => "Home page"])
        );
        return $response->withHeader("Content-Type", "text/html");
    }

    #[Post("/echo")]
    public function echo(ServerRequestInterface $request): Response
    {
        $response = $this->responseFactory->createResponse(HttpStatusCode::StatusOk->value);
        $body = $response->getBody();
        $body->write(
            $request->getBody()
        );

        return $response->withHeader("Content-Type", "application/json");
    }
}
