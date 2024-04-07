<?php

namespace Csu\PsrFramework\Http\Message;

use RuntimeException;
use Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface
{
    private $stream;

    public function __construct($stream)
    {
        if (!is_resource($stream)) {
            $stream = fopen('php://temp', 'r+');
        }
        $this->stream = $stream;
    }

    public function __toString(): string
    {
        if (!$this->isSeekable()) {
            return '';
        }
        $this -> rewind();
        return stream_get_contents($this -> stream);
    }

    public function close(): void
    {
        fclose($this -> stream);
    }

    public function detach()
    {
        $result = $this -> stream;
        $this -> stream = null;
        return $result;
    }

    public function getSize(): ?int
    {
        if (is_resource($this->stream)) {
            $stats = fstat($this->stream);
            return isset($stats['size']) ? $stats['size'] : null;
        }
        return null;
    }

    public function tell(): int
    {
        if (!is_resource($this->stream)) {
            throw new RuntimeException("Stream is not a resource");
        }
        $result = ftell($this->stream);
        if (!is_int($result)) {
            throw new RuntimeException("Error occurred during tell operation");
        }
        return $result;
    }

    public function eof(): bool
    {
        return !$this->stream || $this->eof($this->stream);
    }

    public function isSeekable(): bool
    {
        $meta = stream_get_meta_data($this->stream);
        return $meta['seekable'];
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        if (!$this->isSeekable()) {
            throw new RuntimeException("Stream is not seekable");
        }
        fseek($this->stream, $offset, $whence);
    }

    public function rewind(): void
    {
        $this->seek(0);
    }

    public function isWritable(): bool
    {
        $meta = stream_get_meta_data($this->stream);
        $mode = $meta['mode'];
        return strpos($mode, 'w') !== false
            || strpos($mode, 'a') !== false
            || strpos($mode, 'x') !== false
            || strpos($mode, 'c') !== false
            || strpos($mode, '+') !== false;
    }

    public function write(string $string): int
    {
        if (!$this->isWritable()) {
            throw new RuntimeException("Stream is not writable");
        }
        $result = fwrite($this->stream, $string);
        if ($result === false) {
            throw new RuntimeException("Error writing to stream");
        }
        return $result;
    }

    public function isReadable(): bool
    {
        $meta = stream_get_meta_data($this->stream);
        $mode = $meta['mode'];
        return strpos($mode, 'r') !== false
            || strpos($mode, '+') !== false;
    }

    public function read(int $length): string
    {
        if (!$this->isReadable()) {
            throw new RuntimeException("Stream is not readable");
        }
        $result = fread($this->stream);
        return $result !== false ? $result : '';
    }

    public function getContents(): string
    {
        if (!$this->isReadable()) {
            throw new RuntimeException("Stream is not readable");
        }
        $contents = stream_get_contents($this->stream);
        return $contents !== false ? $contents : '';
    }

    public function getMetadata(?string $key = null)
    {
        $meta = stream_get_meta_data($this->stream);
        if ($key === null) {
            return $meta;
        }
        return isset($meta[$key]) ? $meta[$key] : null;
    }
}
