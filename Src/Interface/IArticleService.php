<?php

namespace App\Src\Interface;

use App\Src\Model\Article;
use App\Src\Model\ArticlePayload;
use App\Src\Model\BaseModel;

interface IArticleService
{
    public function createArticle(Article $data, string $userId): BaseModel;
    public function getArticle(ArticlePayload $payload): array;
    public function getArticleById(string $categoryId, int $newsId, string $slug): ?Article;
}
