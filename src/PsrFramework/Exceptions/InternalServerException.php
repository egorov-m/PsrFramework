<?php

namespace Csu\PsrFramework\Exceptions;

use Exception;
use Psr\Container\ContainerExceptionInterface;

class InternalServerException extends Exception implements ContainerExceptionInterface
{
}
