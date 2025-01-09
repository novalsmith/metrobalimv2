<?php

namespace App\Src\Service;

use App\Src\Interface\IArticleRepository;
use App\Src\Interface\IArticleService;
use App\Src\Model\Article;
use App\Src\Model\ArticlePayload;
use App\Src\Model\BaseModel;

class ArticleService implements IArticleService
{
    private IArticleRepository $articleRepository;

    public function __construct(IArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }
    public function createArticle(Article $data, string $userId): BaseModel
    {
        try {
            $register = $this->articleRepository->createArticle($data, $userId);
            return $register;
        } catch (\Exception $e) {
            throw new \Exception('Error create article : ' . $e->getMessage(), 500);
        }
    }

    public function getArticle(ArticlePayload $payload): array
    {
        try {
            $register = $this->articleRepository->getArticle($payload);
            return $register;
        } catch (\Exception $e) {
            throw new \Exception('Error when getPage : ' . $e->getMessage(), 500);
        }
    }

    public function getArticleById(string $categoryId, int $newsId, string $slug): ?Article
    {
        try {
            $register = $this->articleRepository->getArticleById($categoryId, $newsId, $slug);
            return $register;
        } catch (\Exception $e) {
            throw new \Exception('Error when getPageById : ' . $e->getMessage(), 500);
        }
    }
}
