<?php

namespace App\Src\Model;

class Category
{
    public int $categoryId;
    public string $categoryName;
    public ?int $parentId;
    public ?string $parentCategory;

    public function __construct(int $category_id = 0, string $category_name = 'uncategory', string $createdBy = 'system', $parentId = null, $parentCategory = null)
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

    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    public function getParentCategory(): ?string
    {
        return $this->parentCategory;
    }
}
