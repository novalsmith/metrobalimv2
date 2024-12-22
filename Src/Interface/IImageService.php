<?php

namespace App\Src\Interface;

use App\Src\Model\BaseModel;
use App\Src\Model\Tag;

interface IImageService
{
    public function upload(array $request): array;
    public function get(Tag $data, string $userId): BaseModel;
    public function delete(string $id): BaseModel;
}
