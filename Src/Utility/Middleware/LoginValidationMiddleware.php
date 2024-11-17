<?php

namespace App\Src\Utility\Middleware;

use App\Src\Utility\Helper\JsonResponseHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Respect\Validation\Validator as v;
use Slim\Psr7\Factory\ResponseFactory;

class LoginValidationMiddleware
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

        // Validasi username: Tidak boleh kosong dan panjang karakter minimal 3
        if (!array_key_exists('username', $data) || empty($data['username'])) {
            $errors['username'] = 'Username tidak boleh kosong.';
        } elseif (!v::length(5, null)->validate($data['username'])) {
            $errors['username'] = 'Username minimal 3 karakter.';
        }

        // Validasi password: Tidak boleh kosong, panjang minimal 5 karakter, dan harus mengandung huruf, angka, serta simbol
        if (!array_key_exists('password', $data) || empty($data['password'])) {
            $errors['password'] = 'Password tidak boleh kosong.';
        } elseif (!v::length(5, null)->validate($data['password'])) {
            $errors['password'] = 'Password harus memiliki minimal 5 karakter.';
        } elseif (!preg_match('/[a-zA-Z]/', $data['password'])) {
            $errors['password'] = 'Password harus mengandung setidaknya satu huruf.';
        } elseif (!preg_match('/\d/', $data['password'])) {
            $errors['password'] = 'Password harus mengandung setidaknya satu angka.';
        } elseif (!preg_match('/[\W_]/', $data['password'])) {
            $errors['password'] = 'Password harus mengandung setidaknya satu simbol.';
        }
        return $errors;
    }
}
