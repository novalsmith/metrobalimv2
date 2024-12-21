<?php

namespace App\Src\Interface;

use App\Src\Model\BaseModel;
use App\Src\Model\Tag;

interface ITagRepository
{
    public function getTags(): array;
    public function createTag(Tag $data, string $userId): BaseModel;
    public function deleteTagByID(string $id): BaseModel;
}
