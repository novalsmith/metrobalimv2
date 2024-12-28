<?php

namespace App\Src\Model;

use App\Src\Utility\Config\Constant;
use DateTime;

class Image
{
    public ?string $imageId;
    public ?string $fileName;
    public ?string $alt;
    public ?string $descriptions;
    public ?string $imageUrl;
    public ?string $imageThumbUrl;

    public function __construct(array $data)
    {
        $this->imageId = $data['imageId'];
        $this->fileName = $data['fileName'] ?? null;
        $this->alt = $data['altText'] ?? null;
        $this->descriptions = $data['description'] ?? null;
        $this->imageUrl = $this->setImageUrl($data['fileName'], $data['utcCreatedDate'], false);
        $this->imageThumbUrl = $this->setImageUrl($data['fileName'], $data['utcCreatedDate'], true);
    }

    private function setImageUrl(string $fileName, $createdDate, bool $isThumb)
    {
        $date = new DateTime($createdDate);
        $year = $date->format('Y');
        $month = $date->format('m');
        if ($isThumb) {
            return Constant::BaseUrl . Constant::ImageThumbnailPath . DIRECTORY_SEPARATOR . $year . DIRECTORY_SEPARATOR . $month . DIRECTORY_SEPARATOR . $fileName;
        } else {
            return Constant::BaseUrl . Constant::ImagePath . DIRECTORY_SEPARATOR . $year . DIRECTORY_SEPARATOR . $month . DIRECTORY_SEPARATOR . $fileName;
        }
    }

}
