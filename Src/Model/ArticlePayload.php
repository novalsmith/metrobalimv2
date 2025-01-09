<?php

namespace App\Src\Model;

use DateTime;

class ArticlePayload
{
    public ?int $newsId;
    public ?string $keywords;
    public ?int $categoryId;
    public ?string $status;
    public ?DateTime $startDate;
    public ?DateTime $endDate;
    public ?string $slug;
    public ?int $page;
    public ?int $pageSize;

    public function __construct(array $data)
    {
        $this->newsId = $data['newsId'] ?? null;
        $this->keywords = $data['keywords'] ?? '';
        $this->slug = $data['slug'];
        $this->categoryId = $data['categoryId'] ?? null;
        $this->status = $data['status'] ?? 'draft';
        $this->startDate = $data['startDate'] ?? null;
        $this->endDate = $data['endDate'] ?? '';
        $this->page = $data['page'] ?? null;
        $this->pageSize = $data['pageSize'] ?? null;
    }
}
