<?php

namespace Csu\PsrFramework\Server\Attributes;

use Attribute;
use Csu\PsrFramework\Server\Enums\HttpMethod;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Route
{
    public function __construct(public string $routePath, public HttpMethod $method = HttpMethod::Get)
    {
    }
}
