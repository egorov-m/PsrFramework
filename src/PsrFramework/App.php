<?php

namespace Csu\PsrFramework;

use Csu\PsrFramework\Exceptions\RouteNotFoundException;
use Csu\PsrFramework\Http\Server\Router;
use Jenssegers\Blade\Blade;
use Psr\Container\ContainerInterface;

class App
{
    public function __construct(
        protected ContainerInterface $components,
        protected Router $router,
        protected array $request,
        protected array $config
    )
    {

    }

    public function run()
    {
        try {
            echo $this->router->resolve($this->request["uri"], strtolower($this->request["method"]));
        } catch (RouteNotFoundException) {
            http_response_code(404);

            echo $this->components->get(Blade::class)->render("error/404");
        }
    }
}
