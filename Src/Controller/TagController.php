<?php

namespace App\Src\Controller;

use App\Src\Interface\ITagService;
use App\Src\Model\Tag;
use App\Src\Model\Validator\TagValidator;
use App\Src\Utility\Helper\JsonResponseHelper;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TagController
{
    private ITagService $tagService;

    public function __construct(ITagService $tagService)
    {
        $this->tagService = $tagService;
    }

    public function getTags(Request $request, Response $response): Response
    {
        $tags = $this->tagService->getTags();
        return JsonResponseHelper::respondWithData($response, $tags);
    }

    public function createTag(Request $request, Response $response): Response
    {

        try {
            $parsedBody = $request->getParsedBody();
            $userData = $request->getAttribute("userContext");

            TagValidator::validate($parsedBody);

            $data = new Tag($parsedBody);

            $categories = $this->tagService->createTag($data, $userData->userId);
            return JsonResponseHelper::respondWithData($response, $categories);
        } catch (InvalidArgumentException $e) {
            return JsonResponseHelper::respondWithError($response, $e->getMessage(), 400);
        }
    }

    public function deleteCategoryById(Request $request, Response $response, $arg): Response
    {
        $parsedBody = $request->getParsedBody();
        $categories = $this->tagService->deleteTagByID($parsedBody["id"]);
        return JsonResponseHelper::respondWithData($response, $categories);
    }
}
