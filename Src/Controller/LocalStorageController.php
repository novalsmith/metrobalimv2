<?php

namespace App\Src\Controller;

use App\Src\Interface\ILocalStorageService;
use App\Src\Utility\Helper\JsonResponseHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LocalStorageController
{
    private ILocalStorageService $service;

    public function __construct(ILocalStorageService $service)
    {
        $this->service = $service;
    }

    public function getAllCache(Request $request, Response $response): Response
    {
        $result = $this->service->getAll();
        return JsonResponseHelper::respondWithData($response, $result);
    }

    public function getCacheById(Request $request, Response $response): Response
    {
        $id = $request->getAttribute('id');
        $result = $this->service->getById($id);
        return JsonResponseHelper::respondWithData($response, $result);
    }

    public function deleteCacheById(Request $request, Response $response): Response
    {
        $id = $request->getAttribute('id');
        $result = $this->service->getById($id);
        return JsonResponseHelper::respondWithData($response, $result);
    }

    public function deleteCache(Request $request, Response $response): Response
    {
        $result = $this->service->deleteAll();
        return JsonResponseHelper::respondWithData($response, $result);
    }
}
