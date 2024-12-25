<?php

namespace App\Src\Controller;

use App\Src\Interface\IImageService;
use App\Src\Model\Image;
use App\Src\Model\Validator\ImageValidator;
use App\Src\Utility\Helper\JsonResponseHelper;
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
        $uploadedFiles = $request->getUploadedFiles();

        $parsedBody = $request->getParsedBody();
        $userData = $request->getAttribute("userContext");
        $payloadValidation = [
            'fileName' => $uploadedFiles["fileName"],
            'alt' => $parsedBody["alt"],
            'description' => $parsedBody["description"],
        ];

        try {
            // Validasi data menggunakan CategoryValidator
            ImageValidator::validate($payloadValidation);

            // Jika validasi sukses, buat model Category
            $parseModel = new Image($parsedBody);

            $responseData = $this->imageService->uploadImage($uploadedFiles, $parseModel);
            return JsonResponseHelper::respondWithData($response, $responseData);
        } catch (InvalidArgumentException $e) {
            return JsonResponseHelper::respondWithError($response, $e->getMessage(), 400);
        }

    }
}
