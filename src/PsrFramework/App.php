<?php

namespace Csu\PsrFramework;

use Csu\PsrFramework\Exception\ConfigException;
use Psr\Container\ContainerInterface;

use Csu\PsrFramework\Di\ComponentContainer;

class App
{
    public array $params;
    public ContainerInterface $components;

    /**
     * @throws ConfigException
     */
    public function init()
    {
        if (! array_key_exists("components", $this->params)) {
            throw new ConfigException(
                "The config must contain the components."
            );
        }

        $this->components=new ComponentContainer(
            $this->params["components"]
        );
    }

    public function run(): string
    {
        return "Hello, World!";
    }
}
