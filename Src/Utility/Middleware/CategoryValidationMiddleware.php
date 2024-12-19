<?php

namespace App\Src\Utility\Middleware;

use App\Src\Utility\Helper\JsonResponseHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Factory\ResponseFactory;

class CategoryValidationMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        // Ambil data dari request body
        $data = json_decode($request->getBody()->getContents(), true);

        // Lakukan validasi langsung pada data
        $errors = $this->validate($data);

        // Jika ada error, kembalikan response dengan status 400
        if (!empty($errors)) {
            $responseFactory = new ResponseFactory();
            $response = $responseFactory->createResponse();
            return JsonResponseHelper::respondWithData($response, $errors, 400);
        }
        return $handler->handle($request);
    }

    // Fungsi validasi dengan menggunakan Respect\Validation\Validator
    private function validate(array $data): array
    {
        $errors = [];

        // Validasi nama kategori: Tidak boleh kosong dan alfanumerik
        if (!array_key_exists('name', $data) || empty($data['name'])) {
            $errors['name'] = 'Nama kategori tidak boleh kosong.';
        } elseif (!preg_match('/^[a-zA-Z0-9 ]+$/', $data['name'])) {
            $errors['name'] = 'Nama kategori harus berupa alfanumerik tanpa karakter khusus.';
        }

        return $errors;
    }
}
