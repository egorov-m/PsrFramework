<?php

namespace Csu\PsrFramework\Server;

use Psr\Http\Message\ResponseInterface;

interface EmitterInterface
{
    public function emit(ResponseInterface $response, bool $withoutBody = false): void;
}
