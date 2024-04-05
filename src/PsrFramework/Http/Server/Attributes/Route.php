<?php

namespace Csu\PsrFramework\Http\Server\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD|Attribute::IS_REPEATABLE)]
class Route
{
    public function __construct(public string $routePath, public string $method = "get")
    {

    }
}
