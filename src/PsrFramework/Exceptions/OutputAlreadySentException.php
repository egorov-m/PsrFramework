<?php

namespace Csu\PsrFramework\Exceptions;

use Exception;

use Psr\Container\ContainerExceptionInterface;

class OutputAlreadySentException extends Exception implements ContainerExceptionInterface
{

}
