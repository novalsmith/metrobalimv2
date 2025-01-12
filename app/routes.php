<?php

declare (strict_types = 1);

use App\Src\Controller\ArticleController;
use App\Src\Controller\AuthController;
use App\Src\Controller\CategoryController;
use App\Src\Controller\ImageController;
use App\Src\Controller\LocalStorageController;
use App\Src\Controller\PageController;
use App\Src\Controller\TagController;
use App\Src\Model\Validator\ArticlePayloadValidator;
use App\Src\Model\Validator\ArticleValidator;
use App\Src\Model\Validator\AuthLoginValidator;
use App\Src\Model\Validator\AuthRegisterValidator;
use App\Src\Model\Validator\CategoryValidator;
use App\Src\Model\Validator\ImageValidator;
use App\Src\Model\Validator\PageValidator;
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

    // Image
    $app->group('/image', function (Group $group) {
        $group->post('/list', [ImageController::class, 'listImage']);
        $group->delete('', [ImageController::class, 'deleteImage']);
        $group->post('/upload', [ImageController::class, 'upload'])
            ->add(new ValidationMiddleware(ImageValidator::class))
            ->add(ImageMiddleware::class);
    });

    // Page
    $app->group('/page', function (Group $group) {
        $group->get('', [PageController::class, 'getPage']);
        $group->get('/{slug}', [PageController::class, 'getPageById']);
        $group->post('/create', [PageController::class, 'createPage'])
            ->add(new ValidationMiddleware(PageValidator::class));
    });

    // Article
    $app->group('/article', function (Group $group) {
        $group->post('/search', [ArticleController::class, 'searchArticle'])
            ->add(new ValidationMiddleware(ArticlePayloadValidator::class));
        $group->get('/{categoryId}/{newsId}/{slug}', [ArticleController::class, 'getArticleById']);
        $group->post('/create', [ArticleController::class, 'createArticle'])
            ->add(new ValidationMiddleware(ArticleValidator::class));
    });
};
