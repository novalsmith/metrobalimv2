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
        $data->setName($parsedBody['name']);
        $data->setUserName($parsedBody['userName']);
        $data->setEmail($parsedBody['email']);
        $data->setPassword($parsedBody['password']);

        // Meneruskan model yang sudah diisi ke service
        $result = $this->authService->register($data);

        return JsonResponseHelper::respondWithData($response, $result);
    }

    public function login(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();

            $param = new AuthRegister;
            $param->setUserName($data['username']);
            $param->setPassword($data['password']);

            $result = $this->authService->getUserAuth($param);

            return JsonResponseHelper::respondWithData($response, $result->accessToken);
        } catch (\Exception $e) {
            return JsonResponseHelper::respondWithError($response, $e->getMessage(), $e->getCode(), Constant::ERROR_STATUS);
        }
    }

    public function logout(Request $request, Response $response): Response
    {

        try {
            // Ambil userData dari request yang diset oleh JwtMiddleware
            $userData = $request->getAttribute("userData");
            $token = $request->getHeaderLine('Authorization');

            $userId = $userData->userId; // Pastikan $userData bukan null
            $ttl = $userData->exp - time();

            $result = $this->authService->revokeToken($userId, $ttl, $token);

            return JsonResponseHelper::respondWithData($response, $result->getData());
        } catch (\Exception $e) {
            return JsonResponseHelper::respondWithError($response, $e->getMessage(), $e->getCode(), Constant::ERROR_STATUS);
        }
    }

    public function refreshToken(Request $request, Response $response): Response
    {
        $token = $request->getHeaderLine('Authorization');
        $result = $this->authService->refreshToken($token);

        return JsonResponseHelper::respondWithData($response, $result->getData());
    }
}
