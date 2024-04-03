<?php

namespace Csu\PsrFramework\Di;

abstract class BaseComponent implements IComponent
{
    public function __construct(array $params)
    {
        $this->init();
    }

    abstract public function init(): void;
}
