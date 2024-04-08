<?php

namespace Csu\PsrFramework\Server\Exceptions;

use Exception;
use Psr\Container\ContainerExceptionInterface;

class HeadersAlreadySentException extends Exception implements ContainerExceptionInterface
{
}
