<?php

declare (strict_types = 1);

use App\Src\Controller\AuthController;
use App\Src\Controller\CategoryController;
use App\Src\Controller\ImageController;
use App\Src\Controller\LocalStorageController;
use App\Src\Controller\TagController;
use App\Src\Model\Validator\AuthLoginValidator;
use App\Src\Model\Validator\AuthRegisterValidator;
use App\Src\Model\Validator\CategoryValidator;
use App\Src\Model\Validator\TagValidator;
use App\Src\Utility\Middleware\ImageMiddleware;
use App\Src\Utility\Middleware\JwtMiddleware;
use App\Src\Utility\Middleware\ValidationMiddleware;
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
        $group->post('/register', [AuthController::class, 'register'])
            ->add(new ValidationMiddleware(AuthRegisterValidator::class));

        $group->post('/login', [AuthController::class, 'login'])
            ->add(new ValidationMiddleware(AuthLoginValidator::class));

        $group->post('/refreshToken', [AuthController::class, 'refreshToken']);

        $group->post('/logout', [AuthController::class, 'logout'])->add(JwtMiddleware::class);
    });

    // Categories
    $app->group('/category', function (Group $group) {
        $group->get('', [CategoryController::class, 'getCategory']);
        $group->get('/{id}', [CategoryController::class, 'getCategoryById']);
        $group->delete('', [CategoryController::class, 'deleteCategoryById']);
        $group->post('', [CategoryController::class, 'createCategory'])
            ->add(new ValidationMiddleware(CategoryValidator::class));

    });

    // Tag
    $app->group('/tag', function (Group $group) {
        $group->get('', [TagController::class, 'getTags']);
        $group->delete('/{id}', [TagController::class, 'deleteTagById']);
        $group->post('', [TagController::class, 'createTag'])
            ->add(new ValidationMiddleware(TagValidator::class));
    });

    // Local Storage
    $app->group('/cache', function (Group $group) {
        $group->get('/populatemasterdata', [LocalStorageController::class, 'populateMasterData']);
        $group->get('/{id}', [LocalStorageController::class, 'getCacheById']);
        $group->delete('/{id}', [LocalStorageController::class, 'removeCacheById']);
        $group->get('', [LocalStorageController::class, 'getAllCache']);
        $group->delete('', [LocalStorageController::class, 'deleteCache']);
    })->add(JwtMiddleware::class);

    // Local Storage
    $app->group('/image', function (Group $group) {
        $group->post('/upload', [ImageController::class, 'upload']);
    })->add(ImageMiddleware::class);

};
