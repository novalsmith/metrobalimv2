<?php

namespace App\Src\Service;

use App\Src\Interface\ITagRepository;
use App\Src\Interface\ITagService;
use App\Src\Model\BaseModel;
use App\Src\Model\DTO\TagDTO;
use App\Src\Model\Tag;

class TagService implements ITagService
{
    private ITagRepository $tagRepository;

    public function __construct(ITagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function getTags(): array
    {
        $data = $this->tagRepository->getTags();
        return array_map(function ($response) {
            return new TagDTO($response->getTagCode(), $response->getTagName());
        }, $data);
    }

    public function createTag(Tag $data, string $userId): BaseModel
    {
        $data = $this->tagRepository->createTag($data, $userId);
        return $data;
    }

    public function deleteTagById(string $id): BaseModel
    {
        $data = $this->tagRepository->deleteTagById($id);
        return $data;
    }

}
