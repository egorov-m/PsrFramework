<?php

namespace Csu\PsrFramework\Http\Server;

use Psr\Http\Message\ResponseInterface;

interface EmitterInterface
{
    public function emit(ResponseInterface $response, bool $withoutBody = false): void;
}
