<?php

namespace Csu\App\Middleware;

use Csu\PsrFramework\Server\Exceptions\ForbiddenException;
use Csu\PsrFramework\Server\Exceptions\UnauthorizedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        protected array $protectedPaths = [],
        protected array $validUser = ["username" => "user@m.r", "authenticated" => true]
    ) {
    }

    /**
     * @throws UnauthorizedException
     * @throws ForbiddenException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (in_array($request -> getUri()->getPath(), $this->protectedPaths)) {
            $username = $_SESSION['username'] ?? false;
            $authenticated = $_SESSION['authenticated'] ?? false;

            if (!$authenticated || !$username) {
                throw new UnauthorizedException();
            }
            if ($this->validUser["username"] == $username && $this->validUser["authenticated"] == $authenticated) {
                return $handler->handle($request);
            } else {
                throw new ForbiddenException();
            }
        }

        return $handler->handle($request);
    }
}
