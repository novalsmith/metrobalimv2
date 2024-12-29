<?php

declare (strict_types = 1);

use App\Src\Controller\AuthController;
use App\Src\Controller\CategoryController;
use App\Src\Controller\ImageController;
use App\Src\Controller\LocalStorageController;
use App\Src\Controller\PageController;
use App\Src\Controller\TagController;
use App\Src\Interface\IAuthRepository;
use App\Src\Interface\IAuthService;
use App\Src\Interface\ICategoryRepository;
use App\Src\Interface\ICategoryService;
use App\Src\Interface\IImageRepository;
use App\Src\Interface\IImageService;
use App\Src\Interface\ILocalStorageService;
use App\Src\Interface\IPageRepository;
use App\Src\Interface\IPageService;
use App\Src\Interface\ITagRepository;
use App\Src\Interface\ITagService;
use App\Src\Repository\AuthRepository;
use App\Src\Repository\CategoryRepository;
use App\Src\Repository\ImageRepository;
use App\Src\Repository\PageRepository;
use App\Src\Repository\TagRepository;
use App\Src\Service\AuthService;
use App\Src\Service\CategoryService;
use App\Src\Service\ImageService;
use App\Src\Service\LocalStorageService;
use App\Src\Service\PageService;
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
        IImageService::class => \DI\autowire(ImageService::class),
        IPageService::class => \DI\autowire(PageService::class),

        // Autowiring Repositories
        ICategoryRepository::class => \DI\autowire(CategoryRepository::class),
        ITagRepository::class => \DI\autowire(TagRepository::class),
        IAuthRepository::class => \DI\autowire(AuthRepository::class),
        IImageRepository::class => \DI\autowire(ImageRepository::class),
        IPageRepository::class => \DI\autowire(PageRepository::class),

        // Controllers with their dependencies injected
        CategoryController::class => \DI\autowire(),
        TagController::class => \DI\autowire(),
        AuthController::class => \DI\autowire(),
        LocalStorageController::class => \DI\autowire(),
        ImageController::class => \DI\autowire(),
        PageController::class => \DI\autowire(),

        // Utility
        CacheHelper::class => \DI\autowire(),
    ]);
};
