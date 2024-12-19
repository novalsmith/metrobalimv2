<?php

namespace App\Src\Interface;

use App\Src\Model\BaseModel;
use App\Src\Model\Category;

interface ICategoryService
{
    public function getCategories(): array;
    public function createCategory(Category $data, string $userId): BaseModel;
    public function deleteCategoryByID(string $id): BaseModel;
}
