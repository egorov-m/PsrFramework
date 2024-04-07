<?php

namespace Csu\PsrFramework\Http\Server\Attributes;

use Attribute;
use Csu\PsrFramework\Enums\HttpMethod;

#[Attribute(Attribute::TARGET_METHOD|Attribute::IS_REPEATABLE)]
class Post extends Route
{
    public function __construct(string $routePath)
    {
        parent::__construct($routePath, HttpMethod::Post);
    }
}
