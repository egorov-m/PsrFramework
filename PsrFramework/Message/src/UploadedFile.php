<?php

namespace Csu\PsrFramework\Message;

use Exception;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;

class UploadedFile implements UploadedFileInterface
{
    private StreamInterface $stream;
    private ?int $size;
    private int $error;
    private ?string $clientFileName;
    private ?string $clientMediaType;

    public function __construct($stream, $size, $error, $clientFileName, $clientMediaType)
    {
        $this->stream = $stream;
        $this->size = $size;
        $this->error = $error;
        $this->clientFileName = $clientFileName;
        $this->clientMediaType = $clientMediaType;
    }
    public function getStream(): StreamInterface
    {
        return $this->stream;
    }

    /**
     * @throws Exception
     */
    public function moveTo(string $targetPath): void
    {
        if (!move_uploaded_file($this->stream, $targetPath)) {
            throw new Exception("Failed to move uploaded file");
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
