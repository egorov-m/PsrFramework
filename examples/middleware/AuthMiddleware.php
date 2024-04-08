<?php

namespace Csu\PsrFramework\Examples\middleware;

use Csu\PsrFramework\Exceptions\ForbiddenException;
use Csu\PsrFramework\Exceptions\UnauthorizedException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public  function __construct(
        protected array  $protectedPathes = [],
        protected  array $validUser = ["username"=>"user@m.r", "authenticated"=>true]
    )
    {
    }

    /**
     * @throws UnauthorizedException
     * @throws ForbiddenException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (in_array($request -> getUri()->getPath(), $this->protectedPathes)){
            // Получение данных из сессии
            $username = $_SESSION['username'] ?? false;
            $authenticated = $_SESSION['authenticated'] ?? false;

            if (!$authenticated || !$username) {
                throw new UnauthorizedException();
            }
            if($this->validUser["username"]==$username && $this->validUser["authenticated"]==$authenticated){
                return $handler->handle($request);
            }
            else{
                throw new ForbiddenException();
            }
        }

        return $handler->handle($request);
    }
}