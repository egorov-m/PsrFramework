<?php

namespace Csu\PsrFramework\Http\Message;

use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\StreamInterface;

class UploadedFile implements UploadedFileInterface
{

    public function getStream(): StreamInterface
    {
        // TODO: Implement getStream() method.
    }

    public function moveTo(string $targetPath): void
    {
        // TODO: Implement moveTo() method.
    }

    public function getSize(): ?int
    {
        // TODO: Implement getSize() method.
    }

    public function getError(): int
    {
        // TODO: Implement getError() method.
    }

    public function getClientFilename(): ?string
    {
        // TODO: Implement getClientFilename() method.
    }

    public function getClientMediaType(): ?string
    {
        // TODO: Implement getClientMediaType() method.
    }
}
