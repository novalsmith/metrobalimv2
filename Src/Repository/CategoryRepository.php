<?php

namespace App\Src\Repository;

use App\Src\Interface\ICategoryRepository;
use App\Src\Model\BaseModel;
use App\Src\Model\Category;
use App\Src\Utility\Config\Constant;

class CategoryRepository extends BaseRepository implements ICategoryRepository
{
    public function getCategories(): array
    {
        return $this->executeQueryFetchAll(Constant::SPCategory_GetCategory, [], Category::class);
    }

    public function createCategory(Category $data, string $userId): BaseModel
    {
        $params = [
            $data->getCategoryName(),
            $data->getParentId(),
            $userId,
        ];
        return $this->executeQueryFetchObject(Constant::SPCategory_CreateCategory, $params, BaseModel::class);
    }

    public function deleteCategoryById(string $id): BaseModel
    {
        $params = [
            $id,
        ];
        return $this->executeQueryFetchObject(Constant::SPCategory_DeleteCategory, $params, BaseModel::class);
    }
}
