<?php

declare (strict_types = 1);

use App\Src\Controller\AuthController;
use App\Src\Controller\CategoryController;
use App\Src\Controller\LocalStorageController;
use App\Src\Controller\TagController;
use App\Src\Interface\IAuthRepository;
use App\Src\Interface\IAuthService;
use App\Src\Interface\ICategoryRepository;
use App\Src\Interface\ICategoryService;
use App\Src\Interface\ILocalStorageService;
use App\Src\Interface\ITagRepository;
use App\Src\Interface\ITagService;
use App\Src\Repository\AuthRepository;
use App\Src\Repository\CategoryRepository;
use App\Src\Repository\TagRepository;
use App\Src\Service\AuthService;
use App\Src\Service\CategoryService;
use App\Src\Service\LocalStorageService;
use App\Src\Service\TagService;
use App\Src\Utility\Helper\CacheHelper;
use App\Src\Utility\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        // Logger definition with autowiring
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

        // Autowiring Services
        ICategoryService::class => \DI\autowire(CategoryService::class),
        ITagService::class => \DI\autowire(TagService::class),
        IAuthService::class => \DI\autowire(AuthService::class),
        ILocalStorageService::class => \DI\autowire(LocalStorageService::class),

        // Autowiring Repositories
        ICategoryRepository::class => \DI\autowire(CategoryRepository::class),
        ITagRepository::class => \DI\autowire(TagRepository::class),
        IAuthRepository::class => \DI\autowire(AuthRepository::class),

        // Controllers with their dependencies injected
        CategoryController::class => \DI\autowire(),
        TagController::class => \DI\autowire(),
        AuthController::class => \DI\autowire(),
        LocalStorageController::class => \DI\autowire(),

        // Utility
        CacheHelper::class => \DI\autowire(),
    ]);
};
