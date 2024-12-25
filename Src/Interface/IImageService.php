<?php

namespace App\Src\Interface;
use App\Src\Model\Image;

interface IImageService
{
    public function uploadImage(array $request, Image $payload): array;
}
