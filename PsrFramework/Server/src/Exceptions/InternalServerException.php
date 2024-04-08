<?php

namespace Csu\PsrFramework\Server\Exceptions;

use Exception;
use Psr\Container\ContainerExceptionInterface;

class InternalServerException extends Exception implements ContainerExceptionInterface
{
}
