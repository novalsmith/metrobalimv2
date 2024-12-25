<?php

namespace App\Src\Service;

use App\Src\Interface\IImageRepository;
use App\Src\Interface\IImageService;
use App\Src\Model\Image;
use App\Src\Utility\Config\Constant;
use Intervention\Image\ImageManager;

class ImageService implements IImageService
{
    private IImageRepository $imageRepository;

    public function __construct(IImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
        // $this->imageService = $imageService;
    }

    public function uploadImage(array $uploadedFiles, Image $payload): array
    {
        try {
            $responseData = [];
            foreach ($uploadedFiles as $uploadedFile) {
                if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                    $originalName = pathinfo($uploadedFile->getClientFilename(), PATHINFO_FILENAME);
                    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
                    $newName = bin2hex(random_bytes(8)) . "." . $extension;

                    $newPath = Constant::ImagePath . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . date('m') . DIRECTORY_SEPARATOR . $newName;
                    ImageManager::gd()->read($newPath)
                        ->scale(Constant::ImageRatioArticle_Height, Constant::ImageRatioArticle_Width);

                    $uploadedFile->moveTo($newPath);

                    $thumbnailName = "thumb_" . $newName;
                    $thumbnailFullPath = Constant::ImageThumbnailPath . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . date('m') . DIRECTORY_SEPARATOR . $thumbnailName;

                    ImageManager::gd()->read($newPath)
                        ->scale(800, 600)->save($thumbnailFullPath);

                    $responseData = [
                        'decription' => $newPath,
                        'altText' => $newPath,
                        'imagePath' => $newPath,
                        'imageThumbPath' => $thumbnailFullPath,
                    ];
                    // $new = new Image($responseData);
                    $this->imageRepository->uploadImage($responseData);
                }
            }

            return $responseData;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}
