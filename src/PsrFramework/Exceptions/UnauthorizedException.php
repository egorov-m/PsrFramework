<?php

namespace Csu\PsrFramework\Exceptions;

use Exception;
use Psr\Container\ContainerExceptionInterface;

class UnauthorizedException extends Exception implements ContainerExceptionInterface
{
}
