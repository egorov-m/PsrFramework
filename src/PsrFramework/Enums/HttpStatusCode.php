<?php

namespace Csu\PsrFramework\Enums;

enum HttpStatusCode: int
{
    case StatusOk = 200;
    case StatusCreated = 201;
    case StatusNoContent = 204;
    case StatusBadRequest = 400;
    case StatusUnauthorized = 401;
    case StatusForbidden = 403;
    case StatusNotFound = 404;
    case StatusMethodNotAllowed = 405;
    case StatusInternalServerError = 500;
    case StatusNotImplemented = 501;
    case StatusSeeOther = 303;
}
