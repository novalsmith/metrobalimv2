<?php

namespace App\Src\Interface;

use App\Src\Model\BaseModel;
use DateTime;

interface IImageRepository
{
    public function uploadImage(array $image);
    public function getImage(?int $imageId, ?DateTime $date): array;
    public function deleteImage(int $imageId): BaseModel;

}
