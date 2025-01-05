<?php

namespace App\Src\Service;

use App\Src\Interface\IArticleRepository;
use App\Src\Interface\IArticleService;
use App\Src\Model\Article;
use App\Src\Model\BaseModel;

class ArticleService implements IArticleService
{
    private IArticleRepository $pageRepository;

    public function __construct(IArticleRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }
    public function createArticle(Article $data, string $userId): BaseModel
    {
        try {
            $register = $this->pageRepository->createArticle($data, $userId);
            return $register;
        } catch (\Exception $e) {
            throw new \Exception('Error create article : ' . $e->getMessage(), 500);
        }
    }
}
