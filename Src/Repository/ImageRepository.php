<?php

namespace App\Src\Repository;

use App\Src\Interface\IImageRepository;
use App\Src\Model\BaseModel;

class ImageRepository extends BaseRepository implements IImageRepository
{

    public function uploadImage(array $data): BaseModel
    {
        $param = [
            $data["fileName"],
            $data["description"],
            $data["alt"],
            'system',
        ];
        return $this->executeQueryFetchObject("CreateImage", $param, BaseModel::class);
    }
}
