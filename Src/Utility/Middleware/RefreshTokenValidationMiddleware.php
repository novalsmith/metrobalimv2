<?php

namespace App\Src\Utility\Middleware;

use App\Src\Utility\Helper\JsonResponseHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Factory\ResponseFactory;

class RefreshTokenValidationMiddleware
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

        // Jika tidak ada error, lanjutkan ke handler berikutnya
        return $handler->handle($request);
    }

    // Fungsi validasi dengan menggunakan Respect\Validation\Validator
    private function validate(array $data): array
    {
        $errors = [];

        if (!array_key_exists('refreshToken', $data) || empty($data['refreshToken'])) {
            $errors['refreshToken'] = 'Token tidak boleh kosong';
        }

        return $errors;
    }
}
