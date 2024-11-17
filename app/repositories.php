<?php

declare (strict_types = 1);

use App\Src\Interface\IAuthRepository;
use App\Src\Interface\ICategoryRepository;
use App\Src\Repository\AuthRepository;
use App\Src\Repository\CategoryRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        ICategoryRepository::class => \DI\autowire(CategoryRepository::class),
        IAuthRepository::class => \DI\autowire(AuthRepository::class),
    ]);
};
