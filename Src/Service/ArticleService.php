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
            $offset = ($payload->page - 1) * $payload->pageSize;
            $articles = $this->articleRepository->getArticle($payload, $offset);

            // Total jumlah data dari database
            $total = $this->articleRepository->getTotalData($payload);

            // Apakah masih ada halaman berikutnya
            $more = ($offset + $payload->pageSize) < $total->data;

            // Susun respons dengan struktur yang benar
            $response = [
                'news' => $articles,
                'meta' => [
                    'page' => $payload->page,
                    'pageSize' => $payload->pageSize,
                    'more' => $more,
                    'total' => $total->data,
                    'offset' => $offset,
                ],
            ];

            return $response;
        } catch (\Exception $e) {
            throw new \Exception('Error when getArticle : ' . $e->getMessage(), 500);
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
