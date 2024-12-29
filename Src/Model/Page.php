<?php

namespace App\Src\Model;

use App\Src\Utility\Helper\UtilityHelper;

class Page
{
    public ?int $parent;
    public ?Image $image;
    public ?int $imageCover;
    public string $title;
    public int $order;
    public ?string $slug;
    public string $metaDescription;
    public string $metaKeywords;
    public string $content;
    public string $status;
    public ?string $publishDate;

    public function __construct(array $data)
    {
        $this->parent = $data['parent'];
        $this->title = $data['title'] ?? null;
        $this->slug = UtilityHelper::slugify($data['title']);
        $this->order = $data['order'] ?? null;
        $this->metaDescription = $data['metaDescription'] ?? null;
        $this->metaKeywords = $data['metaKeywords'] ?? null;
        $this->content = $data['content'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->publishDate = $data['publishDate'] ?? null;
        $this->imageCover = $data['imageCover'] ?? null;
        if (!empty($data['fileName'])) {
            $this->image = new Image($data);
        }
    }
}
