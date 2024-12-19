<?php

namespace App\Src\Model;

class Category
{
    private ?int $categoryId = 0;
    private string $categoryName;
    private ?int $parentId = null;
    private ?string $parentCategory = null;

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

    public function setId($categoryId): int
    {
        return $this->categoryId = $categoryId;
    }

    public function setName($categoryName): string
    {
        return $this->categoryName = $categoryName;
    }

    public function setParentId($parentId): ?int
    {
        return $this->parentId = $parentId;
    }

    public function setParentCategory($parentCategory): ?string
    {
        return $this->parentCategory = $parentCategory;
    }
}
