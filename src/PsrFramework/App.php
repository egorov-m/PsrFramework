<?php

namespace Csu\PsrFramework;

use Csu\PsrFramework\Http\Server\Router;
use Psr\Container\ContainerInterface;

class App
{
    public function __construct(
        protected ContainerInterface $components,
        protected Router $router,
        protected array $config
    )
    {

    }

    public function run()
    {
        $this->router->run();
    }
}
