<?php

namespace Csu\PsrFramework\Http\Message;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class Message implements MessageInterface
{
    protected array $headers = [];
    protected ?StreamInterface $body = null;

    public function __construct(?string $body = null)
    {
        $this->body = $body !== null ? new Stream($body) : null;
    }

    protected array $headerNameMapping = [];
    protected string $protocolVersion = '1.1';
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        $clone = clone $this;
        $clone->protocolVersion = $version;

        return $clone;
    }

    public function getHeaders(): array
    {
        $normalizedHeaders = [];

        foreach ($this->headerNameMapping as $originalName => $normalizedName) {
            $normalizedName = strtolower($normalizedName);
            $originalValue = $this->headers[$normalizedName] ?? null;

            if ($originalValue !== null) {
                $normalizedHeaders[$originalName] = $originalValue;
            }
        }

        return $normalizedHeaders;
    }

    public function hasHeader(string $name): bool
    {
        return isset($this->headers[strtolower($name)]);
    }

    public function getHeader(string $name): array
    {
        $normalizedName = strtolower($name);

        return $this->headers[$normalizedName] ?? [];
    }

    public function getHeaderLine(string $name): string
    {
        return implode(', ', $this->getHeader($name));
    }

    public function withHeader(string $name, $value): MessageInterface
    {
        $normalizedHeaderName = $this->normalizeHeaderFieldName($name);

        return (clone $this)->setHeader($normalizedHeaderName, $value, $name);
    }

    protected function setHeader(string $name, $value, string $originalName): MessageInterface
    {
        $clone = clone $this;
        $clone->headers[$name] = $value;
        $clone->headerNameMapping[$name] = $originalName;

        return $clone;
    }

    public function withAddedHeader(string $name, $value): MessageInterface
    {
        $normalizedHeaderName = $this->normalizeHeaderFieldName($name);
        $normalizedHeaderValue = $this->normalizeHeaderFieldValue($value);

        $clone = clone $this;
        $clone->headerNameMapping[$normalizedHeaderName] = $name;
        $clone->headers[$normalizedHeaderName] = ($clone->headers[$normalizedHeaderName] ?? []) + $normalizedHeaderValue;

        return $clone;
    }

    public function withoutHeader(string $name): MessageInterface
    {
        $normalizedHeaderName = strtolower($name);
        $origName = $name;

        $clone = clone $this;
        unset($clone->headers[$normalizedHeaderName]);
        unset($clone->headerNameMapping[$normalizedHeaderName]);

        return $clone;
    }

    public function getBody(): StreamInterface
    {
        if ($this->body === null) {
            return new Stream('php://temp', 'r+');
        }

        return $this->body;
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        $clone = clone $this;
        $clone->body = $body;

        return $clone;
    }

    private function normalizeHeaderFieldName(string $name): string
    {
        $this->assertHeaderFieldName($name);
        return trim(strtolower($name));
    }

    private function normalizeHeaderFieldValue(array|string $value): array
    {
        $this->assertHeaderFieldValue($value);

        if (is_string($value)) {
            return [trim($value)];
        }

        if (is_array($value)) {
            return array_map('trim', $value);
        }

        return [(string) $value];
    }

    private function assertHeaderFieldValue(array|string $value): void
    {
        if (is_array($value)) {
            foreach ($value as $item) {
                $this->assertSingleHeaderFieldValue($item);
            }
        } else {
            $this->assertSingleHeaderFieldValue($value);
        }
    }

    private function assertSingleHeaderFieldValue(string $value): void
    {
        if ($value === '') {
            throw new \InvalidArgumentException('Empty header value is not allowed.');
        }

        if (!preg_match('/^[ \t\x21-\x7e]+$/', $value)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '"%s" is not a valid header value. It must contain visible ASCII characters only.',
                    $value
                )
            );
        }
    }

    private function assertHeaderFieldName(string $name): void
    {
        if ($name === '') {
            throw new \InvalidArgumentException('Empty header name is not allowed.');
        }

        if (!preg_match('/^[a-zA-Z0-9!#$%&\'*+-.^_`|~]+$/', $name)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '"%s" is not a valid header name. It must be an RFC 7230 compatible string.',
                    $name
                )
            );
        }
    }
}
