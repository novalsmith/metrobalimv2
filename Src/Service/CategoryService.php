<?php

namespace App\Src\Service;

use App\Src\DTO\CategoryDTO;
use App\Src\Interface\ICategoryRepository;
use App\Src\Interface\ICategoryService;

class CategoryService implements ICategoryService
{
    private ICategoryRepository $categoryRepository;

    public function __construct(ICategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories(): array
    {
        // Ambil data dari repository
        $categories = $this->categoryRepository->getAllCategories();

        // Menggunakan array_map untuk transformasi menjadi DTO
        return array_map(function ($category) {
            return new CategoryDTO($category->getId(), $category->getName(), $category->getParentId(), $category->getParentCategory());
        }, $categories);

        // return $categories;

    }

}
