<?php

namespace Csu\PsrFramework\Message\src\Factory;

use Csu\PsrFramework\Message\Uri;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

class UriFactory implements UriFactoryInterface
{

    public function createUri(string $uri = ''): UriInterface
    {
        return new Uri($uri);
    }
}
