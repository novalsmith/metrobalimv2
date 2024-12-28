<?php

namespace App\Src\Service;

use App\Src\Interface\IImageRepository;
use App\Src\Interface\IImageService;
use App\Src\Model\BaseModel;
use App\Src\Model\Validator\ImageValidator;
use App\Src\Utility\Config\Constant;
use App\Src\Utility\Helper\UtilityHelper;
use DateTime;
use Exception;
use Intervention\Image\ImageManager;
use Psr\Http\Message\ServerRequestInterface as Request;

class ImageService implements IImageService
{
    private IImageRepository $imageRepository;

    public function __construct(IImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    public function uploadImage(Request $request): array
    {
        try {
            $uploadedFiles = $request->getUploadedFiles();
            $parsedBody = $request->getParsedBody();
            $userData = $request->getAttribute("userContext");

            if (!isset($uploadedFiles["fileName"])) {
                throw new Exception("File tidak ditemukan");
            }

            $uploadedFile = $uploadedFiles["fileName"];
            $payloadValidation = [
                'fileName' => $uploadedFile,
                'alt' => $parsedBody["alt"] ?? null,
                'descriptons' => $parsedBody["descriptons"] ?? null,
            ];

            ImageValidator::validate($payloadValidation);

            $responseData = [];
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
                $newImageName = bin2hex(random_bytes(8)) . "." . $extension;

                $newPath = UtilityHelper::imagePath($newImageName);
                $uploadedFile->moveTo($newPath);

                $thumbnailFullPath = UtilityHelper::imageThumbPath($newImageName);
                ImageManager::gd()->read($newPath)
                    ->scale(Constant::ImageRatioSmall_Width, Constant::ImageRatioSmall_Height)->save($thumbnailFullPath);

                $responseData = [
                    $newImageName,
                    $parsedBody["alt"],
                    $parsedBody["descriptons"],
                    $userData->userId,
                ];
                $this->imageRepository->uploadImage($responseData);
            }
            return $responseData;
        } catch (Exception $e) {
            throw new Exception('Internal Server Error: ' . $e->getMessage(), 500);
        }
    }

    public function getImage(?int $imageId, ?DateTime $date): array
    {
        try {
            return $this->imageRepository->getImage($imageId, $date);
        } catch (\Exception $e) {
            throw new \Exception('Error fetching image: ' . $e->getMessage(), 500);
        }
    }

    public function deleteImage(int $imageId): BaseModel
    {
        try {
            $imageData = $this->imageRepository->getImage($imageId, null);
            if (!$imageData) {
                throw new Exception("Data tidak ditemukan");
            }

            if (!$imageData[0]) {
                throw new Exception("Data tidak ditemukan");
            }

            $imagePath = UtilityHelper::imagePath($imageData[0]->fileName);
            $thumbnailFullPath = UtilityHelper::imageThumbPath($imageData[0]->fileName);

            if (file_exists($imagePath)) {
                if (!unlink($imagePath)) {
                    throw new Exception("File gagal dihapus");
                }
            } else {
                throw new Exception("File tidak ditemukan");
            }

            if (file_exists($thumbnailFullPath)) {
                if (!unlink($thumbnailFullPath)) {
                    throw new Exception("File thumbnail gagal dihapus");
                }
            } else {
                throw new Exception("File thumbnail tidak ditemukan");
            }

            return $this->imageRepository->deleteImage($imageId);

        } catch (Exception $e) {
            throw new Exception('Internal Server Error: ' . $e->getMessage(), 500);
        }
    }
}
