<?php

namespace Csu\PsrFramework\Http\Message\Factory;

use Csu\PsrFramework\Http\Message\Stream;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

class StreamFactory implements StreamFactoryInterface
{
    public function createStream(string $content = ''): StreamInterface
    {
        $stream = fopen('data://text/plain,'. $content, 'r+');
        return new Stream($stream);
    }

    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        $stream = fopen($filename, $mode);
        return new Stream($stream);
    }

    public function createStreamFromResource($resource): StreamInterface
    {
        return new Stream($resource);
    }
}
