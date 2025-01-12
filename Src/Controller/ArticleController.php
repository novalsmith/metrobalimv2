<?php

namespace App\Src\Controller;

use App\Src\Interface\IArticleService;
use App\Src\Model\Article;
use App\Src\Model\ArticlePayload;
use App\Src\Model\Validator\ArticlePayloadValidator;
use App\Src\Model\Validator\ArticleValidator;
use App\Src\Utility\Config\Constant;
use App\Src\Utility\Helper\JsonResponseHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ArticleController
{
    private IArticleService $articleService;

    public function __construct(IArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function createArticle(Request $request, Response $response): Response
    {
        try {
            $parsedBody = $request->getParsedBody();
            ArticleValidator::validate($parsedBody);

            $userData = $request->getAttribute("userContext");
            $data = new Article($parsedBody);
            $result = $this->articleService->createArticle($data, $userData->userId);
            return JsonResponseHelper::respondWithData($response, $result);

        } catch (\Exception $e) {
            return JsonResponseHelper::respondWithError($response, $e->getMessage(), $e->getCode(), Constant::ERROR_STATUS);
        }
    }

    public function searchArticle(Request $request, Response $response): Response
    {
        try {
            $parsedBody = $request->getParsedBody();
            ArticlePayloadValidator::validate($parsedBody);

            $payload = new ArticlePayload($parsedBody);

            $result = $this->articleService->getArticle($payload);
            return JsonResponseHelper::respondWithData($response, $result);

        } catch (\Exception $e) {
            return JsonResponseHelper::respondWithError($response, $e->getMessage(), $e->getCode(), Constant::ERROR_STATUS);
        }
    }

    public function getArticleById(Request $request, Response $response): Response
    {
        try {
            // $parsedBody = $request->getParsedBody();
            $categoryId = $request->getAttribute('categoryId');
            $newsId = $request->getAttribute('newsId');
            $slug = $request->getAttribute('slug');
            $result = $this->articleService->getArticleById($categoryId, $newsId, $slug);
            return JsonResponseHelper::respondWithData($response, $result);

        } catch (\Exception $e) {
            return JsonResponseHelper::respondWithError($response, $e->getMessage(), $e->getCode(), Constant::ERROR_STATUS);
        }
    }
}
