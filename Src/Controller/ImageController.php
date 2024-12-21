<?php

namespace App\Src\Controller;

use App\Src\Interface\ICategoryService;
use App\Src\Utility\Config\Constant;
use App\Src\Utility\Helper\JsonResponseHelper;
use Intervention\Image\ImageManager;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ImageController
{
    private ICategoryService $categoryService;

    public function __construct(ICategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function upload(Request $request, Response $response): Response
    {
        try {
            $uploadedFiles = $request->getUploadedFiles();
            $responseData = [];

            foreach ($uploadedFiles as $uploadedFile) {
                if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                    $originalName = pathinfo($uploadedFile->getClientFilename(), PATHINFO_FILENAME);
                    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
                    $newName = bin2hex(random_bytes(8)) . "." . $extension;

                    $uploadedFile->moveTo(Constant::ImagePath . DIRECTORY_SEPARATOR . $newName);

                    $thumbnailName = "thumb_" . $newName;
                    $thumbnailFullPath = Constant::ImageThumbnailPath . DIRECTORY_SEPARATOR . $thumbnailName;

                    // $manager = new ImageManager(new Driver());

                    // create new image instance with 800 x 600 (4:3)
                    $image = ImageManager::gd()->read(Constant::ImagePath . DIRECTORY_SEPARATOR . $newName);

                    // scale to 120 x 100 pixel
                    $image->scale(120, 100); // 120 x 90 (4:3)
                    $image->save($thumbnailFullPath);

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
