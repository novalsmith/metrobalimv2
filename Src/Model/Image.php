<?php

namespace App\Src\Model;

class Image
{
    public string $fileName;
    public string $alt;
    public string $description;
    public string $imageUrl;

    public function __construct(array $data)
    {
        $this->fileName = $data['fileName'] ?? null;
        $this->alt = $data['alt'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->imageUrl = $data['imageUrl'] ?? null;
    }
}
