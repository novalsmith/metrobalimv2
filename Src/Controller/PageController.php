<?php

namespace App\Src\Controller;

use App\Src\Interface\IPageService;
use App\Src\Model\Page;
use App\Src\Model\Validator\PageValidator;
use App\Src\Utility\Config\Constant;
use App\Src\Utility\Helper\JsonResponseHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PageController
{
    private IPageService $pageService;

    public function __construct(IPageService $pageService)
    {
        $this->pageService = $pageService;
    }

    public function createPage(Request $request, Response $response): Response
    {
        try {
            $parsedBody = $request->getParsedBody();
            PageValidator::validate($parsedBody);

            $userData = $request->getAttribute("userContext");
            $data = new Page($parsedBody);
            $result = $this->pageService->createPage($data, $userData->userId);
            return JsonResponseHelper::respondWithData($response, $result);

        } catch (\Exception $e) {
            return JsonResponseHelper::respondWithError($response, $e->getMessage(), $e->getCode(), Constant::ERROR_STATUS);
        }
    }

    public function getPage(Request $request, Response $response): Response
    {
        try {
            $result = $this->pageService->getPage();
            return JsonResponseHelper::respondWithData($response, $result);

        } catch (\Exception $e) {
            return JsonResponseHelper::respondWithError($response, $e->getMessage(), $e->getCode(), Constant::ERROR_STATUS);
        }
    }

    public function getPageById(Request $request, Response $response): Response
    {
        try {
            // $parsedBody = $request->getParsedBody();
            $slug = $request->getAttribute('slug');
            $result = $this->pageService->getPageById($slug);
            return JsonResponseHelper::respondWithData($response, $result);

        } catch (\Exception $e) {
            return JsonResponseHelper::respondWithError($response, $e->getMessage(), $e->getCode(), Constant::ERROR_STATUS);
        }
    }
}
