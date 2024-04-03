<?php

namespace Csu\PsrFramework\Di;

interface IComponent {
    public function __construct(array $params);
    public function init(): void;
}
