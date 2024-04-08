<?php

namespace Csu\PsrFramework\Examples\Controllers;

use Csu\PsrFramework\Enums\HttpStatusCode;
use Csu\PsrFramework\Examples\middleware\AuthSession;
use Csu\PsrFramework\Http\Message\Factory\ResponseFactory;
use Csu\PsrFramework\Http\Message\Response;
use Csu\PsrFramework\Http\Server\Attributes\Post;
use Psr\Http\Message\ServerRequestInterface;

readonly class AuthController{


    public  function __construct(protected AuthSession $session, private ResponseFactory $responseFactory)
    {

    }

    #[Post("/auth")]
    public  function index(ServerRequestInterface $request):Response
    {
        $parsedArray = $request -> getParsedBody();
        $username = $parsedArray['email'];
        $password = $parsedArray['password'];

        // Создаем экземпляр класса и вызываем метод createSession
        $this -> session ->createSession($username, $password);

        $response = $this->responseFactory->createResponse(HttpStatusCode::StatusSeeOther->value);

        $response = $response->withHeader("Content-Type", "text/html");
        $response =  $response->withHeader("Location", "http://localhost:44480/");

        return $response->withHeader("Content-Type", "text/html");


    }
}
