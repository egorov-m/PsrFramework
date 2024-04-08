<?php

namespace Csu\App\Controllers;

use Csu\App\Middleware\AuthSession;
use Csu\PsrFramework\Message\Factory\ResponseFactory;
use Csu\PsrFramework\Message\Response;
use Csu\PsrFramework\Server\Enums\HttpStatusCode;
use Csu\PsrFramework\Server\Attributes\Post;
use Psr\Http\Message\ServerRequestInterface;

readonly class AuthController
{
    public function __construct(protected AuthSession $session, private ResponseFactory $responseFactory)
    {
    }

    #[Post("/auth")]
    public function index(ServerRequestInterface $request): Response
    {
        $parsedArray = $request -> getParsedBody();
        $username = $parsedArray['email'];
        $password = $parsedArray['password'];

        $this -> session ->createSession($username, $password);

        $response = $this->responseFactory->createResponse(HttpStatusCode::StatusSeeOther->value);

        $response = $response->withHeader("Content-Type", "text/html");
        $response =  $response->withHeader("Location", "http://localhost:44480/");

        return $response->withHeader("Content-Type", "text/html");
    }
}
