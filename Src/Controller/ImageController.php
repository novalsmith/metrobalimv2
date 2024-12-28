<?php

namespace App\Src\Controller;

use App\Src\Interface\IImageService;
use App\Src\Utility\Helper\JsonResponseHelper;
use DateTime;
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
            $responseData = $this->imageService->uploadImage($request);
            return JsonResponseHelper::respondWithData($response, $responseData);
        } catch (InvalidArgumentException $e) {
            return JsonResponseHelper::respondWithError($response, $e->getMessage(), 400);
        } catch (\Exception $e) {
            return JsonResponseHelper::respondWithError($response, 'Internal Server Error', 500);
        }
    }

    public function listImage(Request $request, Response $response): Response
    {
        try {
            $parsedBody = $request->getParsedBody();
            $monthYear = null;
            $imageId = null;

            if (empty($parsedBody)) {
                throw new InvalidArgumentException('Payload tidak boleh kosong');
            }

            if (isset($parsedBody["dateFilter"])) {
                $dateFilter = $parsedBody["dateFilter"];
                try {
                    $dateObject = new DateTime($dateFilter);
                    $monthYear = $dateObject;
                } catch (\Exception $e) {
                    throw new InvalidArgumentException('Tanggal tidak valid');
                }
            } else if (isset($parsedBody["imageId"])) {
                $imageId = $parsedBody["imageId"];
            }

            $responseData = $this->imageService->getImage($imageId, $monthYear);
            return JsonResponseHelper::respondWithData($response, $responseData);
        } catch (InvalidArgumentException $e) {
            return JsonResponseHelper::respondWithError($response, $e->getMessage(), 400);
        } catch (\Exception $e) {
            return JsonResponseHelper::respondWithError($response, 'Internal Server Error', 500);
        }
    }

    public function deleteImage(Request $request, Response $response): Response
    {
        try {
            $parsedBody = $request->getParsedBody();

            if (empty($parsedBody)) {
                throw new InvalidArgumentException('Payload tidak boleh kosong');
            }

            if (empty($parsedBody["imageId"])) {
                throw new InvalidArgumentException('Id tidak boleh kosong');
            }

            $responseData = $this->imageService->deleteImage($parsedBody["imageId"]);
            return JsonResponseHelper::respondWithData($response, $responseData);
        } catch (InvalidArgumentException $e) {
            return JsonResponseHelper::respondWithError($response, $e->getMessage(), 400);
        } catch (\Exception $e) {
            return JsonResponseHelper::respondWithError($response, $e->getMessage(), $e->getCode());
        }
    }
}
