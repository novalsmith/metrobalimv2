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

    public function getArticles($page = 1, $per_page = 1000)
    {
        $api_key = "54482a2c4da0c689f586a7fd081752554de867cb";
        $url = "https://rss.promediateknologi.id/api/article?apikey=$api_key&page=$page&per_page=$per_page";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        // Asumsi data yang dikembalikan adalah JSON
        return json_decode($response, true);

        // if ($data) {
        //     return $data['articles']; // Sesuaikan dengan struktur JSON yang sebenarnya
        // } else {
        //     return [];
        // }

    }
}
