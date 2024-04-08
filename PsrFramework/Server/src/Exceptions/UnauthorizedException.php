<?php

namespace Csu\PsrFramework\Server\Exceptions;

use Exception;
use Psr\Container\ContainerExceptionInterface;

class UnauthorizedException extends Exception implements ContainerExceptionInterface
{
}
