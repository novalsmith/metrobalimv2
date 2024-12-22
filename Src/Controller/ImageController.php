<?php

namespace App\Src\Controller;

use App\Src\Interface\IImageService;
use App\Src\Utility\Config\Constant;
use App\Src\Utility\Helper\JsonResponseHelper;
use Intervention\Image\ImageManager;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ImageController
{
    private IImageService $imageService;

    public function __construct(IImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function upload(Request $request, Response $response): Response
    {
        try {
            $uploadedFiles = $request->getUploadedFiles();
            $responseData = [];

            $this->imageService->upload($uploadedFiles);

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

                    $responseData[] = [
                        'original_name' => $originalName,
                        'file_url' => Constant::ImagePath . "/$newName",
                        'thumbnail_url' => Constant::ImageThumbnailPath . "/$thumbnailName",
                    ];

                }
            }
            return JsonResponseHelper::respondWithData($response, $responseData);
        } catch (InvalidArgumentException $e) {
            return JsonResponseHelper::respondWithError($response, $e->getMessage(), 400);
        }

        // $response->getBody()->write(json_encode(['message' => 'Files uploaded successfully', 'data' => $responseData]));
        // return $response->withHeader('Content-Type', 'application/json');
    }
}
