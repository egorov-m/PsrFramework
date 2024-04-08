<?php

namespace Csu\PsrFramework\Examples\middleware;

use Csu\PsrFramework\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;

class AuthMiddleware implements MiddlewareInterface
{
    public  function __construct(protected array $protectedControllers = [])
    {
    }

    /**
     * @throws ReflectionException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {


        $reflectionMethod = new ReflectionMethod($handler, "handle");
        $controllerClassMethod = $reflectionMethod->getDeclaringClass()->getName();

        if (array_key_exists($controllerClassMethod, $this->protectedControllers)){
            // Получение данных из сессии
            $username = $_SESSION['username'] ?? false;
            $authenticated = $_SESSION['authenticated'] ?? false;
            if (!$authenticated || !$username) {
                throw new
            }
        }

        $reflection = new ReflectionFunction($handler->handle);


// Проверка наличия данных в сессии
        if ($authenticated && $username!=null) {
            // Если аутентификация прошла успешно
            // Передача параметра username в атрибуты запроса для последующего использования
            $request = $request->withAttribute('username', $username);
            header("Location", "http://localhost:44480/");
            return $handler->handle($request);

        } else {
            // Если пользователь не аутентифицирован, перенаправляем на разные страницы
            if ($username) {
                // Если есть какие-то данные в сессии, перенаправляем на первую страницу
                header('Location: path_to_first_page.php');
                exit();
            } else {
                // Если сессия пуста или нет нужных данных, перенаправляем на вторую страницу
                header('Location: path_to_second_page.php');
                exit();
            }
        }
    }
}