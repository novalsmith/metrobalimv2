<?php

declare (strict_types = 1);

use App\Src\Controller\AuthController;
use App\Src\Controller\CategoryController;
use App\Src\Controller\LocalStorageController;
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
        return $response;
    });

    // Auth
    $app->group('/auth', function (Group $group) {
        $group->post('/register', [AuthController::class, 'register'])->add(RegisterValidationMiddleware::class);
        $group->post('/login', [AuthController::class, 'login'])->add(LoginValidationMiddleware::class);
        $group->post('/refreshToken', [AuthController::class, 'refreshToken'])->add(RefreshTokenValidationMiddleware::class);
        $group->post('/logout', [AuthController::class, 'logout'])->add(JwtMiddleware::class);
    });

    // Categories
    $app->group('/categories', function (Group $group) {
        $group->get('', [CategoryController::class, 'getCategories']);
        $group->get('/{id}', [CategoryController::class, 'getCategoryById']);
        $group->delete('/{id}', [CategoryController::class, 'deleteCategoryById']);
        $group->post('', [CategoryController::class, 'createCategory'])
            ->add(CategoryValidationMiddleware::class);
    });

    // Local Storage
    $app->group('/cache', function (Group $group) {
        $group->get('/populatemasterdata', [LocalStorageController::class, 'populateMasterData']);
        $group->get('/{id}', [LocalStorageController::class, 'getCacheById']);
        $group->delete('/{id}', [LocalStorageController::class, 'removeCacheById']);
        $group->get('', [LocalStorageController::class, 'getAllCache']);
        $group->delete('', [LocalStorageController::class, 'deleteCache']);
    })->add(JwtMiddleware::class);
};
