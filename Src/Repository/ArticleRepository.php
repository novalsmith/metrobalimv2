<?php

namespace App\Src\Repository;

use App\Src\Interface\IArticleRepository;
use App\Src\Model\Article;
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
        return $this->executeQueryFetchObject(Constant::SPArticle_UpsertPage, $params, BaseModel::class);
    }
}
