<?php

namespace App\Src\Interface;

interface ICategoryRepository
{
    public function getAllCategories(): array;
    // public function createCategory(CategoryDTO $categoryDTO): void;
}
