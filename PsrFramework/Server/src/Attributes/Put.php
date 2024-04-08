<?php

namespace Csu\PsrFramework\Server\Attributes;

use Attribute;
use Csu\PsrFramework\Server\Enums\HttpMethod;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Put extends Route
{
    public function __construct(string $routePath)
    {
        parent::__construct($routePath, HttpMethod::Put);
    }
}
