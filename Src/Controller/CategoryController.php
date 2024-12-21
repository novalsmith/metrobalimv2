<?php

namespace App\Src\Controller;

use App\Src\Interface\ICategoryService;
use App\Src\Model\Category;
use App\Src\Model\Validator\CategoryValidator;
use App\Src\Utility\Helper\JsonResponseHelper;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CategoryController
{
    private ICategoryService $categoryService;

    public function __construct(ICategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function getCategory(Request $request, Response $response): Response
    {
        $categories = $this->categoryService->getCategories();
        return JsonResponseHelper::respondWithData($response, $categories);
    }

    public function createCategory(Request $request, Response $response): Response
    {
        $parsedBody = $request->getParsedBody();
        $userData = $request->getAttribute("userContext");

        try {
            // Validasi data menggunakan CategoryValidator
            CategoryValidator::validate($parsedBody);

            // Jika validasi sukses, buat model Category
            $data = new Category($parsedBody);

            $categories = $this->categoryService->createCategory($data, $userData->userId);
            return JsonResponseHelper::respondWithData($response, $categories);
        } catch (InvalidArgumentException $e) {
            return JsonResponseHelper::respondWithError($response, $e->getMessage(), 400);
        }
    }

    public function deleteCategoryById(Request $request, Response $response): Response
    {
        $parsedBody = $request->getParsedBody();
        $categories = $this->categoryService->deleteCategoryById($parsedBody["categoryId"]);
        return JsonResponseHelper::respondWithData($response, $categories);
    }
}
