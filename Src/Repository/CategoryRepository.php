<?php

namespace App\Src\Repository;

use App\Src\Interface\ICategoryRepository;
use App\Src\Model\BaseModel;
use App\Src\Model\Category;

class CategoryRepository extends BaseRepository implements ICategoryRepository
{
    public function getCategories(): array
    {
        return $this->executeQueryFetchAll("GetCategory", [], Category::class);
    }

    public function createCategory(Category $data, string $userId): BaseModel
    {
        $params = [
            $data->getName(),
            $data->getParentId(),
            $userId,
        ];
        return $this->executeQueryFetchObject("CreateCategory", $params, BaseModel::class);
    }

    public function deleteCategoryById(string $id): BaseModel
    {
        $params = [
            $id,
        ];
        return $this->executeQueryFetchObject("DeleteCategory", $params, BaseModel::class);
    }
}
