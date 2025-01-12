<?php

namespace App\Src\Repository;

use App\Src\Interface\IArticleRepository;
use App\Src\Model\Article;
use App\Src\Model\ArticlePayload;
use App\Src\Model\BaseModel;
use App\Src\Utility\Config\Constant;

class ArticleRepository extends BaseRepository implements IArticleRepository
{
    public function createArticle(Article $data, string $userId): BaseModel
    {
        $params = [
            $data->newsId,
            $data->title,
            $data->slug,
            $data->content,
            $data->categoryId,
            $data->imageCover,
            $data->status,
            $data->publishDate,
            $data->metaKeywords,
            $data->metaDescription,
            $userId,
        ];
        return $this->executeQueryFetchObject(Constant::SPArticle_UpsertArticle, $params, BaseModel::class);
    }

    public function getArticle(ArticlePayload $payload, $offset): array
    {
        $params = [
            $payload->newsId,
            $payload->keywords,
            $payload->slug,
            $payload->categoryId,
            $payload->status,
            $payload->startDate,
            $payload->endDate,
            $offset,
            $payload->pageSize,
        ];

        return $this->executeQueryFetchAll(Constant::SPArticle_GetArticle, $params, Article::class);

    }

    public function getArticleById(string $categoryId, int $newsId, string $slug): ?Article
    {
        $params = [
            $categoryId,
            $newsId,
            $slug,
        ];
        return $this->executeQueryFetchObject(Constant::SPArticle_GetArticleById, $params, Article::class);
    }

    public function getTotalData(ArticlePayload $payload): BaseModel
    {
        $params = [
            'article',
            $payload->newsId,
            $payload->keywords,
            $payload->slug,
            $payload->categoryId,
            $payload->status,
            $payload->startDate,
            $payload->endDate,
        ];
        return $this->executeQueryFetchObject(Constant::SPTotalData, $params, BaseModel::class);
    }
}
