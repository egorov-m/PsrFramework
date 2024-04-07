<?php

namespace Csu\PsrFramework\Enums;

enum HttpMethod: string
{
    case Get = "GET";
    case Post = "POST";
    case Put = "PUT";
    case Head = "HEAD";
    case Delete = "DELETE";
}
