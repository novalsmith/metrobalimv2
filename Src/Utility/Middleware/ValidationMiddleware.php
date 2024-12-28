<?php

namespace App\Src\Utility\Middleware;

use App\Src\Utility\Helper\JsonResponseHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Factory\ResponseFactory;

class ValidationMiddleware
{
    private string $modelClass;

    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        // Ambil parsed body (data input value)
        $data = $request->getParsedBody();

        // Ambil uploaded files
        $uploadedFiles = $request->getUploadedFiles();

        // Gabungkan data input value dengan informasi file (jika ada)
        if (!empty($uploadedFiles)) {
            foreach ($uploadedFiles as $key => $file) {
                $data[$key] = $file; // Tambahkan file ke dalam data
            }
        }

        // Pastikan data adalah array
        if (!is_array($data)) {
            $data = []; // Default ke array kosong jika tidak ada data
        }

        try {
            // Panggil metode validasi dari model
            $this->modelClass::validate($data);
        } catch (\InvalidArgumentException $e) {
            $responseFactory = new ResponseFactory();
            $response = $responseFactory->createResponse();
            return JsonResponseHelper::respondWithData(
                $response,
                ['status' => 'error', 'errors' => json_decode($e->getMessage(), true)],
                400
            );
        }

        return $handler->handle($request);
    }
}
