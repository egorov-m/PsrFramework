<?php

namespace Csu\PsrFramework\Http\Message;

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{

    public function getScheme(): string
    {
        // TODO: Implement getScheme() method.
    }

    public function getAuthority(): string
    {
        // TODO: Implement getAuthority() method.
    }

    public function getUserInfo(): string
    {
        // TODO: Implement getUserInfo() method.
    }

    public function getHost(): string
    {
        // TODO: Implement getHost() method.
    }

    public function getPort(): ?int
    {
        // TODO: Implement getPort() method.
    }

    public function getPath(): string
    {
        // TODO: Implement getPath() method.
    }

    public function getQuery(): string
    {
        // TODO: Implement getQuery() method.
    }

    public function getFragment(): string
    {
        // TODO: Implement getFragment() method.
    }

    public function withScheme(string $scheme): UriInterface
    {
        // TODO: Implement withScheme() method.
    }

    public function withUserInfo(string $user, ?string $password = null): UriInterface
    {
        // TODO: Implement withUserInfo() method.
    }

    public function withHost(string $host): UriInterface
    {
        // TODO: Implement withHost() method.
    }

    public function withPort(?int $port): UriInterface
    {
        // TODO: Implement withPort() method.
    }

    public function withPath(string $path): UriInterface
    {
        // TODO: Implement withPath() method.
    }

    public function withQuery(string $query): UriInterface
    {
        // TODO: Implement withQuery() method.
    }

    public function withFragment(string $fragment): UriInterface
    {
        // TODO: Implement withFragment() method.
    }

    public function __toString(): string
    {
        // TODO: Implement __toString() method.
    }
}
