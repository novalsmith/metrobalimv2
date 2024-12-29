<?php

namespace App\Src\Repository;

use App\Src\Interface\ITagRepository;
use App\Src\Model\BaseModel;
use App\Src\Model\Tag;
use App\Src\Utility\Config\Constant;

class TagRepository extends BaseRepository implements ITagRepository
{
    public function getTags(): array
    {
        return $this->executeQueryFetchAll(Constant::SPTag_GetTags, [], Tag::class);
    }

    public function createTag(Tag $data, string $userId): BaseModel
    {
        $params = [
            $data->getTagCode(),
            $data->getTagName(),
            $userId,
        ];
        return $this->executeQueryFetchObject(Constant::SPTag_UpsertTag, $params, BaseModel::class);
    }

    public function deleteTagByID(string $id): BaseModel
    {
        $params = [
            $id,
        ];
        return $this->executeQueryFetchObject(Constant::SPTag_DeleteTag, $params, BaseModel::class);
    }
}
