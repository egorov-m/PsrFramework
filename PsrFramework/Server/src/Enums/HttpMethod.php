<?php

namespace Csu\PsrFramework\Server\Enums;

enum HttpMethod: string
{
    case Get = "GET";
    case Post = "POST";
    case Put = "PUT";
    case Head = "HEAD";
    case Delete = "DELETE";
}
