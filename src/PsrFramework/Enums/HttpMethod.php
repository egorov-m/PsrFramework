<?php

namespace Csu\PsrFramework\Enums;

enum HttpMethod: string
{
    case Get = "get";
    case Post = "post";
    case Put = "put";
    case Head = "head";
    case Delete = "delete";
}
