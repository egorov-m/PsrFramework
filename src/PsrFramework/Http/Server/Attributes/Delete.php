<?php

namespace Csu\PsrFramework\Http\Server\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD|Attribute::IS_REPEATABLE)]
class Delete extends Route
{
    public function __construct(string $routePath)
    {
        parent::__construct($routePath, "delete");
    }
}
