<?php

namespace Csu\PsrFramework\Server\Exceptions;

use Exception;
use Psr\Container\ContainerExceptionInterface;

class ForbiddenException extends Exception implements ContainerExceptionInterface
{
}
