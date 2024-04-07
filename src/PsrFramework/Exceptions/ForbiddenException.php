<?php

namespace Csu\PsrFramework\Exceptions;

use Exception;
use Psr\Container\ContainerExceptionInterface;

class ForbiddenException extends Exception implements ContainerExceptionInterface
{
}
