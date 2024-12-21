<?php

namespace App\Src\Controller;

use App\Src\Interface\IAuthService;
use App\Src\Model\AuthRegister;
use App\Src\Model\Validator\AuthLoginValidator;
use App\Src\Model\Validator\AuthRegisterValidator;
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
        try {
            $parsedBody = $request->getParsedBody();
            AuthRegisterValidator::validate($parsedBody);

            $data = new AuthRegister($parsedBody);
            $result = $this->authService->register($data);
            return JsonResponseHelper::respondWithData($response, $result);

        } catch (\Exception $e) {
            return JsonResponseHelper::respondWithError($response, $e->getMessage(), $e->getCode(), Constant::ERROR_STATUS);
        }
    }

    public function login(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();
            AuthLoginValidator::validate($data);

            // Ambil data dari repository
            $result = $this->authService->validateEmail($data['email']);
            if (empty($result->getUserId())) {
                throw new \Exception('Invalid email.', 400);
            } else if (!password_verify($data['password'], $result->getPassword())) {
                throw new \Exception('Invalid password.', 400);
            }

            $result = $this->authService->generateToken($result->getUserId(), $result->getRoles());

            return JsonResponseHelper::respondWithData($response, $result);
        } catch (\Exception $e) {
            return JsonResponseHelper::respondWithError($response, $e->getMessage(), $e->getCode(), Constant::ERROR_STATUS);
        }
    }

    public function logout(Request $request, Response $response): Response
    {

        try {
            // Ambil userData dari request yang diset oleh JwtMiddleware
            $userData = $request->getAttribute("userContext");
            $token = $request->getHeaderLine('Authorization');
            $userId = $userData->userId; // Pastikan $userData bukan null
            $ttl = $userData->exp - time();

            $result = $this->authService->revokeToken($userId, $ttl, $token);

            return JsonResponseHelper::respondWithData($response, $result);
        } catch (\Exception $e) {
            return JsonResponseHelper::respondWithError($response, $e->getMessage(), $e->getCode(), Constant::ERROR_STATUS);
        }
    }

    public function refreshToken(Request $request, Response $response): Response
    {
        try {
            $token = $request->getHeaderLine('Authorization');

            if (empty($token)) {
                throw new \Exception('Token tidak boleh kosong', 401);
            }

            $result = $this->authService->refreshToken($token);
            $resultToken = $this->authService->generateToken($result->userId, $result->roles);
            return JsonResponseHelper::respondWithData($response, $resultToken);
        } catch (\Exception $e) {
            return JsonResponseHelper::respondWithError($response, $e->getMessage(), $e->getCode(), Constant::TOKEN_EXPIRED);
        }

    }
}
