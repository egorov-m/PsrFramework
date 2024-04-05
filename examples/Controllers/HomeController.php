<?php

namespace Csu\PsrFramework\Examples\Controllers;

use Csu\PsrFramework\Http\Server\Attributes\Get;
use Csu\PsrFramework\Http\Server\View;

class HomeController
{
    #[Get("/")]
    #[Get("/home")]
    public function index(): View
    {
        return View::make("index");
    }
}
