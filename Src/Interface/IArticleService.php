<?php

namespace App\Src\Interface;

use App\Src\Model\Article;
use App\Src\Model\BaseModel;

interface IArticleService
{
    public function createArticle(Article $data, string $userId): BaseModel;
}
