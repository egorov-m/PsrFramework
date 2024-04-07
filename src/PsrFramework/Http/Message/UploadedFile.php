<?php

namespace Csu\PsrFramework\Http\Message;

use http\Exception\RuntimeException;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\StreamInterface;

class UploadedFile implements UploadedFileInterface
{
    private $stream;
    private $size;
    private $error;
    private $clientFileName;
    private $clientMediaType;

    public function __construct($stream, $size, $error, $clientFileName, $clientMediaType)
    {
        $this->stream=$stream;
        $this->size=$size;
        $this->error=$error;
        $this->clientFileName=$clientFileName;
        $this->clientMediaType=$clientMediaType;
    }
    public function getStream(): StreamInterface
    {
        return $this->stream;
    }

    public function moveTo(string $targetPath): void
    {
        if (!move_uploaded_file($this->stream, $targetPath)){
            throw new RuntimeException("Failed to move uploaded file");
        }
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function getError(): int
    {
        return $this->error;
    }

    public function getClientFilename(): ?string
    {
        return $this->clientFileName;
    }

    public function getClientMediaType(): ?string
    {
        return $this->clientMediaType;
    }
}
