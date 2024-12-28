<?php

namespace App\Src\Interface;

use App\Src\Model\BaseModel;
use DateTime;
use Psr\Http\Message\ServerRequestInterface as Request;

interface IImageService
{
    public function uploadImage(Request $request): array;
    public function getImage(?int $imageId, ?DateTime $date): array;
    public function deleteImage(int $imageId): BaseModel;
}
