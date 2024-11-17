<?php

namespace App\Src\Repository;

use App\Src\Interface\ICategoryRepository;
use App\Src\Model\Category;

class CategoryRepository extends BaseRepository implements ICategoryRepository
{

    // Menggunakan Stored Procedure untuk mengambil semua kategori
    public function getAllCategories(): array
    {
        // return $this->fetchAllAsClass("GetCategory", Category::class);
        return $this->executeQueryFetchAll("GetCategory", [], Category::class);

    }

}
