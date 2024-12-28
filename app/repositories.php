<?php

declare (strict_types = 1);

use App\Src\Interface\IAuthRepository;
use App\Src\Interface\ICategoryRepository;
use App\Src\Interface\IImageRepository;
use App\Src\Interface\ITagRepository;
use App\Src\Repository\AuthRepository;
use App\Src\Repository\CategoryRepository;
use App\Src\Repository\ImageRepository;
use App\Src\Repository\TagRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Autowire repositories
    $containerBuilder->addDefinitions([
        ICategoryRepository::class => \DI\autowire(CategoryRepository::class),
        IAuthRepository::class => \DI\autowire(AuthRepository::class),
        ITagRepository::class => \DI\autowire(TagRepository::class),
        IImageRepository::class => \DI\autowire(ImageRepository::class),
    ]);
};
