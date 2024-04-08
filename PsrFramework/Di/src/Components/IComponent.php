<?php

namespace Csu\PsrFramework\Di\Components;

interface IComponent
{
    public function __construct(array $params);
    public function init(): void;
}
