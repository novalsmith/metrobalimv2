<?php

namespace App\Src\Model;

use App\Src\Utility\Helper\UtilityHelper;

// public ?int $parent;
// public ?Image $image;
// public ?int $imageCover;
// public string $title;
// public int $order;
// public ?string $slug;
// public string $metaDescription;
// public string $metaKeywords;
// public string $content;
// public string $status;
// public ?string $publishDate;

// public function __construct(array $data)
// {
//     $this->parent = $data['parent'];
//     $this->title = $data['title'] ?? null;
//     $this->slug = UtilityHelper::slugify($data['title']);
//     $this->order = $data['order'] ?? null;
//     $this->metaDescription = $data['metaDescription'] ?? null;
//     $this->metaKeywords = $data['metaKeywords'] ?? null;
//     $this->content = $data['content'] ?? null;
//     $this->status = $data['status'] ?? null;
//     $this->publishDate = $data['publishDate'] ?? null;
//     $this->imageCover = $data['imageCover'] ?? null;
//     if (!empty($data['fileName'])) {
//         $this->image = new Image($data);
//     }
// }

class Article
{
    public ?int $newsId;
    public string $title;
    public string $slug;
    public string $content;
    public int $categoryId;
    public string $status;
    public ?string $publishDate;
    public string $metaKeywords;
    public string $metaDescription;
    public string $createdBy;
    public string $utcCreatedDate;
    public ?string $updatedBy;
    public ?string $utcUpdatedDate;
    public ?int $imageCover;
    public ?Image $image;

    public function __construct(array $data)
    {
        $this->newsId = $data['newsId'] ?? null;
        $this->title = $data['title'] ?? '';
        $this->slug = UtilityHelper::slugify($data['title']);
        $this->content = $data['content'] ?? '';
        $this->categoryId = $data['categoryId'] ?? 0;
        $this->status = $data['status'] ?? 'draft';
        $this->publishDate = $data['publishDate'] ?? null;
        $this->metaKeywords = $data['metaKeywords'] ?? '';
        $this->metaDescription = $data['metaDescription'] ?? '';
        $this->imageCover = $data['imageCover'] ?? null;
        $this->createdBy = $data['createdBy'] ?? '';
        if (!empty($data['fileName'])) {
            $this->image = new Image($data);
        }
    }
}
