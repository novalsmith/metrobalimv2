<?php

namespace App\Src\Service;

use App\Src\DTO\CategoryDTO;
use App\Src\Interface\ICategoryRepository;
use App\Src\Interface\ICategoryService;
use App\Src\Model\BaseModel;
use App\Src\Model\Category;

class CategoryService implements ICategoryService
{
    private ICategoryRepository $categoryRepository;

    public function __construct(ICategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getCategories(): array
    {
        $categories = $this->categoryRepository->getCategories();

        return array_map(function ($category) {
            return new CategoryDTO($category->getId(), $category->getName(), $category->getParentId(), $category->getParentCategory());
        }, $categories);
    }

    public function createCategory(Category $data, string $userId): BaseModel
    {
        $data = $this->categoryRepository->createCategory($data, $userId);
        return $data;
    }

    public function deleteCategoryById(string $id): BaseModel
    {
        $data = $this->categoryRepository->deleteCategoryById($id);
        return $data;
    }

}
