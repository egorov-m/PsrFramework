<?php

namespace Csu\PsrFramework;

require dirname(__DIR__) . "/vendor/autoload.php";

$app = new App();
echo $app->run();
