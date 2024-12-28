<?php

namespace App\Src\Repository;

use App\Src\Interface\IImageRepository;
use App\Src\Model\BaseModel;
use App\Src\Model\Image;
use DateTime;

class ImageRepository extends BaseRepository implements IImageRepository
{

    public function uploadImage(array $param): BaseModel
    {
        return $this->executeQueryFetchObject("CreateImage", $param, BaseModel::class);
    }

    public function getImage(?int $imageId, ?DateTime $date): array
    {
        $date = $date != null ? $date->format('Y-m') : null;
        $param = [
            $imageId,
            $date,
        ];
        return $this->executeQueryFetchAll("GetImage", $param, Image::class);
    }

    public function deleteImage(int $imageId): BaseModel
    {
        $param = [
            $imageId,
        ];
        return $this->executeQueryFetchObject("DeleteImage", $param, BaseModel::class);
    }
}
