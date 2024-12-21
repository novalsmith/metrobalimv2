<?php

declare (strict_types = 1);

use App\Src\Utility\Middleware\JwtMiddleware;
use App\Src\Utility\Middleware\SessionMiddleware;
use Slim\App;

return function (App $app) {
    // Menambahkan middleware sesi
    $app->add(SessionMiddleware::class);
    $app->add(JwtMiddleware::class);
};
