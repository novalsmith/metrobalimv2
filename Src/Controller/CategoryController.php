<?php

namespace App\Src\Controller;

use App\Src\Interface\ICategoryService;
use App\Src\Model\Category;
use App\Src\Utility\Helper\JsonResponseHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CategoryController
{
    private ICategoryService $categoryService;

    public function __construct(ICategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function getCategories(Request $request, Response $response): Response
    {
        $categories = $this->categoryService->getCategories();
        return JsonResponseHelper::respondWithData($response, $categories);
    }

    public function createCategory(Request $request, Response $response): Response
    {
        $parsedBody = $request->getParsedBody();
        $userData = $request->getAttribute("userContext");

        $data = new Category();
        $data->setName($parsedBody['name']);
        $data->setParentId($parsedBody['parentId'] ?? null);

        $categories = $this->categoryService->createCategory($data, $userData->userId);
        return JsonResponseHelper::respondWithData($response, $categories);
    }

    public function deleteCategoryById(Request $request, Response $response, $arg): Response
    {
        $categories = $this->categoryService->deleteCategoryById($arg["id"]);
        return JsonResponseHelper::respondWithData($response, $categories);
    }
}
