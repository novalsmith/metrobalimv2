<?php

namespace App\Src\Service;

use App\Src\Interface\ICategoryRepository;
use App\Src\Interface\ICategoryService;
use App\Src\Model\BaseModel;
use App\Src\Model\Category;
use App\Src\Model\DTO\CategoryDTO;

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

        return array_map(function ($result) {
            return new CategoryDTO($result->getCategoryId(), $result->getCategoryName(), $result->getParentId(), $result->getParentCategory());
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
