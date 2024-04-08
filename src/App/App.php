<?php

namespace Csu\App;

use Csu\PsrFramework\Server\Router;
use Psr\Container\ContainerInterface;

class App
{
    public function __construct(
        protected ContainerInterface $components,
        protected Router $router,
        protected array $config
    ) {
    }

    public function run(): void
    {
        $this->router->run();
    }
}
