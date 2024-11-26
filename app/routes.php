<?php

declare (strict_types = 1);

use App\Src\Controller\AuthController;
use App\Src\Controller\CategoryController;
use App\Src\Utility\Middleware\CategoryValidationMiddleware;
use App\Src\Utility\Middleware\JwtMiddleware;
use App\Src\Utility\Middleware\LoginValidationMiddleware;
use App\Src\Utility\Middleware\RefreshTokenValidationMiddleware;
use App\Src\Utility\Middleware\RegisterValidationMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    // Auth
    $app->group('/auth', function (Group $group) {
        $group->post('/register', [AuthController::class, 'register'])->add(RegisterValidationMiddleware::class);
        $group->post('/login', [AuthController::class, 'login'])->add(LoginValidationMiddleware::class);
        $group->post('/logout', [AuthController::class, 'logout'])->add(JwtMiddleware::class);
        $group->post('/refreshToken', [AuthController::class, 'refreshToken'])->add(RefreshTokenValidationMiddleware::class);

    });

    // Categories
    $app->group('/categories', function (Group $group) {
        $group->get('', [CategoryController::class, 'getAllCategories']);
        $group->get('/{id}', [CategoryController::class, 'getCategoryById']);
        $group->post('', [CategoryController::class, 'createCategory'])->add(CategoryValidationMiddleware::class);
    });

};
