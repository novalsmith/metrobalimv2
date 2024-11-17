<?php

namespace App\Src\Controller;

use App\Src\Interface\IAuthService;
use App\Src\Model\AuthRegister;
use App\Src\Utility\Config\Constant;
use App\Src\Utility\Helper\JsonResponseHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController
{
    private IAuthService $authService;

    public function __construct(IAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request, Response $response): Response
    {
        // Mendapatkan data dari request body
        $parsedBody = $request->getParsedBody();

        // Membuat instance AuthRegister dan mengisi datanya
        $data = new AuthRegister();
        $data->setName($parsedBody['name'] ?? '');
        $data->setUserName($parsedBody['userName'] ?? '');
        $data->setEmail($parsedBody['email'] ?? '');
        $data->setPassword($parsedBody['password'] ?? '');

        // Meneruskan model yang sudah diisi ke service
        $result = $this->authService->register($data);

        return JsonResponseHelper::respondWithData($response, $result);
    }

    public function login(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        $param = new AuthRegister;
        $param->setUserName($username);
        $param->setPassword($password);
        // Dapatkan pengguna berdasarkan username
        $result = $this->authService->getUserAuth($param);
        if ($result->getStatus() == Constant::ERROR_STATUS) {
            return JsonResponseHelper::respondWithData($response, $result, 400);
        }

        // Buat token JWT
        // $token = JwtHelper::createToken($result->getData());

        $response->getBody()->write(json_encode($result->getData()));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function logout(Request $request, Response $response): Response
    {
        // Ambil userData dari request yang diset oleh JwtMiddleware
        $userData = $request->getAttribute("userData");

        // Jika userData tidak ditemukan, kembalikan error
        // error_log("UserData in Controller: " . json_encode($userData));
        $userId = $userData->userId; // Pastikan $userData bukan null
        // return JsonResponseHelper::respondWithData($response, $userId, 400);

        // Hapus atau nonaktifkan token di tabel auth_tokens
        $isLoggedOut = $this->authService->revokeToken($userId);

        if ($isLoggedOut->getStatus() == Constant::SUCCESS_STATUS) {
            return JsonResponseHelper::respondWithData($response, "Logout berhasil");
        } else {
            return JsonResponseHelper::respondWithData($response, "Gagal melakukan logout", 500);
        }
    }

}
