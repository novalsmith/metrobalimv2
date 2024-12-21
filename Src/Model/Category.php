<?php
namespace App\Src\Model;

class Category
{
    private ?int $categoryId;
    private string $categoryName;
    private ?string $parentCategory;
    private ?int $parentId;

    public function __construct(array $data)
    {
        $this->categoryId = $data['categoryId'] ?? null;
        $this->categoryName = $data['categoryName'];
        $this->parentId = $data['parentId'] ?? null;
        $this->parentCategory = $data['parentCategory'] ?? null;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function getCategoryName(): string
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
