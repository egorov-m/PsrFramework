<?php

namespace Csu\PsrFramework\Examples\Controllers;

use Jenssegers\Blade\Blade;

use Csu\PsrFramework\Http\Server\Attributes\Get;

readonly class HomeController
{
    public function __construct(private Blade $blade)
    {

    }

    #[Get("/")]
    #[Get("/home")]
    public function index(): string
    {
        return $this->blade->render("index", ["title" => "Home page"]);
    }
}
