<?php

namespace Csu\PsrFramework\Message;

use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class Request extends Message implements RequestInterface
{
    protected $requestTarget;
    private string $method = "GET";
    private UriInterface $uri;

    public function __construct(UriInterface $uri, string $method, $body = null)
    {
        parent::__construct($body);
        $this->method = $method;
        $this->uri = $uri;
    }

    public function getRequestTarget(): string
    {
        if ($this->requestTarget) {
            return $this->requestTarget;
        }

        $path = $this->uri->getPath();
        $query = $this->uri->getQuery();

        if (empty($path)) {
            $path = '/';
        }

        if (!empty($query)) {
            $path .= '?' . $query;
        }

        return $path;
    }

    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        if (preg_match('#\s#', $requestTarget)) {
            throw new InvalidArgumentException('Invalid request target provided; cannot contain whitespace');
        }
        $clone = clone $this;
        $uri = $clone->uri->withPath('');
        $uri =  $uri->withQuery('');
        $clone->uri = $uri;

        return $clone;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod(string $method): RequestInterface
    {
        if (!is_string($method) || !preg_match('/^[!#$%&\'*+.^_`|~0-9a-zA-Z-]+$/', $method)) {
            throw new InvalidArgumentException('Invalid HTTP method provided');
        }
        $clone = clone $this;
        $clone->method = $method;

        return $clone;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $clone = clone $this;
        $clone->uri = $uri;

        return $clone;
    }
}
