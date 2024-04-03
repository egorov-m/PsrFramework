<?php

namespace Csu\PsrFramework\Http\Message\Factory;

use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\StreamInterface;

class UploadedFileFactory implements UploadedFileFactoryInterface
{

    public function createUploadedFile(StreamInterface $stream, int $size = null, int $error = \UPLOAD_ERR_OK, string $clientFilename = null, string $clientMediaType = null): UploadedFileInterface
    {
        // TODO: Implement createUploadedFile() method.
    }
}
