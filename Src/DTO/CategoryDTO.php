<?php

namespace App\Src\DTO;

class CategoryDTO
{
    public int $categoryId;
    public string $categoryName;
    public ?int $parentId;
    public ?string $parentCategory;

    public function __construct(int $category_id, string $category_name, int $parentId = null, string $parentCategory = null)
    {
        $this->categoryId = $category_id;
        $this->categoryName = $category_name;
        $this->parentId = $parentId;
        $this->parentCategory = $parentCategory;
    }

    public function getId(): int
    {
        return $this->categoryId;
    }

    public function getName(): string
    {
        return $this->categoryName;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function getParentCategory(): ?string
    {
        return $this->parentCategory;
    }
}
