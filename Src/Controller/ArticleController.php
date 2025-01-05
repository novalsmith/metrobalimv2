<?php

namespace App\Src\Controller;

use App\Src\Interface\IArticleService;
use App\Src\Model\Article;
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

}
