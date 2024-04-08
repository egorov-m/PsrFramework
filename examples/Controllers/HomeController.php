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
        $username = $_SESSION['username'] ?? false;
        $authenticated = $_SESSION['authenticated'] ?? false;
        $response = $this->responseFactory->createResponse(HttpStatusCode::StatusOk->value);
        $body = $response->getBody();
        if($username && $authenticated){
            $body->write(
                $this->blade->render(
                    "main",
                    [
                        "title" => "You authorized : Homepage",
                        "username" => $username
                    ]
                )
            );
        }
        else{
            $body->write(
                $this->blade->render(
                    "main",
                    [
                        "title" => "You not authorized : Homepage",
                        "username" => ""
                    ]
                )
            );
        }

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
    #[Get("/auth")]
    public function auth(): Response
    {
        $username = $_SESSION['username'] ?? false;
        $authenticated = $_SESSION['authenticated'] ?? false;

        if($username && $authenticated){
            $response = $this->responseFactory->createResponse(HttpStatusCode::StatusSeeOther->value);
            return $response->withHeader("Location", "http://localhost:44480/");
        }

        $response = $this->responseFactory->createResponse(HttpStatusCode::StatusOk->value);
        $body = $response->getBody();
        $body->write(
            $this->blade->render("auth", ["title" => "Авторизация"])
            );
        return $response->withHeader("Content-Type", "text/html");
    }
}
