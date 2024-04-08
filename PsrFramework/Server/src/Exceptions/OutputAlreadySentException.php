<?php

namespace Csu\PsrFramework\Server\Exceptions;

use Exception;
use Psr\Container\ContainerExceptionInterface;

class OutputAlreadySentException extends Exception implements ContainerExceptionInterface
{

}
