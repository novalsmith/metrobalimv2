<?php

namespace App\Src\Interface;

use App\Src\Model\BaseModel;

interface ICategoryRepository
{
    public function getAllCategories(): array;
    public function refreshToken(string $userId): BaseModel;
}
