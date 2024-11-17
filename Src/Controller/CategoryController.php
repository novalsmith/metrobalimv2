<?php

namespace App\Src\Controller;

use App\Src\Interface\ICategoryService;
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

    public function getAllCategories(Request $request, Response $response): Response
    {
        $categories = $this->categoryService->getAllCategories();
        return JsonResponseHelper::respondWithData($response, $categories);
    }

    public function createCategory(Request $request, Response $response): Response
    {
        $categories = $this->categoryService->getAllCategories();
        return JsonResponseHelper::respondWithData($response, $categories);
    }
}
