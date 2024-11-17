<?php

namespace App\Src\Utility\Middleware;

use App\Src\Utility\Helper\JsonResponseHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Respect\Validation\Validator as v;
use Slim\Psr7\Factory\ResponseFactory;

class InputValidationMiddleware
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

        // Validasi username: Hanya mengandung alfanumerik
        if (array_key_exists('username', $data) && !preg_match('/^[a-zA-Z0-9]+$/', $data['username'])) {
            $errors['username'] = 'Username harus berupa alfanumerik tanpa karakter khusus.';
        }

        // Validasi password: Harus string
        if (array_key_exists('password', $data) && !v::stringType()->validate($data['password'])) {
            $errors['password'] = 'Password harus berupa string.';
        }

        // Validasi email: Harus format email yang valid
        if (array_key_exists('email', $data) && !v::email()->validate($data['email'])) {
            $errors['email'] = 'Email tidak valid.';
        }

        // Validasi nama kategori: Hanya mengandung alfanumerik dan spasi
        if (array_key_exists('name', $data) && !preg_match('/^[a-zA-Z0-9 ]+$/', $data['name'])) {
            $errors['name'] = 'Nama kategori harus berupa alfanumerik tanpa karakter khusus.';
        }

        return $errors;
    }
}
