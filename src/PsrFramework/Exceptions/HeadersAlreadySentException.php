<?php

namespace Csu\PsrFramework\Exceptions;

use Exception;

use Psr\Container\ContainerExceptionInterface;

class HeadersAlreadySentException extends Exception implements ContainerExceptionInterface
{

}
