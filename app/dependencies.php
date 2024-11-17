<?php

declare (strict_types = 1);

use App\Src\Controller\AuthController;
use App\Src\Controller\CategoryController;
use App\Src\Interface\IAuthRepository;
use App\Src\Interface\IAuthService;
use App\Src\Interface\ICategoryRepository;
use App\Src\Interface\ICategoryService;
use App\Src\Repository\AuthRepository;
use App\Src\Repository\CategoryRepository;
use App\Src\Service\AuthService;
use App\Src\Service\CategoryService;
use App\Src\Utility\Middleware\CategoryValidationMiddleware;
use App\Src\Utility\Middleware\LoginValidationMiddleware;
use App\Src\Utility\Middleware\RegisterValidationMiddleware;
use App\Src\Utility\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },

        // Tambahkan definisi untuk ICategoryService
        ICategoryService::class => function (ContainerInterface $c) {
            // Ambil repository yang diperlukan
            $categoryRepository = $c->get(ICategoryRepository::class);

            // Return instance dari CategoryService dengan dependensi yang diinject
            return new CategoryService($categoryRepository);
        },

        // Definisi untuk ICategoryRepository
        ICategoryRepository::class => function (ContainerInterface $c) {
            // Return instance dari repository yang dibutuhkan
            return new CategoryRepository($c->get('db'));
        },

        // Definisi untuk CategoryController
        CategoryController::class => function (ContainerInterface $c) {
            return new CategoryController($c->get(ICategoryService::class));
        },
        // Auth

        // Tambahkan definisi untuk ICategoryService
        IAuthService::class => function (ContainerInterface $c) {
            // Ambil repository yang diperlukan
            $categoryRepository = $c->get(IAuthRepository::class);

            // Return instance dari CategoryService dengan dependensi yang diinject
            return new AuthService($categoryRepository);
        },

        // Definisi untuk ICategoryRepository
        IAuthRepository::class => function (ContainerInterface $c) {
            // Return instance dari repository yang dibutuhkan
            return new AuthRepository($c->get('db'));
        },

        // Definisi untuk CategoryController
        AuthController::class => function (ContainerInterface $c) {
            return new AuthController($c->get(IAuthService::class));
        },

        // Middleware

        // Definisi untuk CategoryValidationMiddleware
        CategoryValidationMiddleware::class => function (ContainerInterface $c) {
            return new CategoryValidationMiddleware();
        },

        RegisterValidationMiddleware::class => function (ContainerInterface $c) {
            return new RegisterValidationMiddleware();
        },

        LoginValidationMiddleware::class => function (ContainerInterface $c) {
            return new LoginValidationMiddleware();
        },

    ]);
};
