<?php

namespace App\Src\Service;

use App\Src\Interface\IAuthRepository;
use App\Src\Interface\IAuthService;
use App\Src\Model\AuthRegister;
use App\Src\Model\BaseModel;
use App\Src\Utility\Config\Constant;
use App\Src\Utility\Helper\CacheHelper;
use App\Src\Utility\Helper\JwtHelper;
use Slim\Psr7\Request;

class AuthService implements IAuthService
{
    private IAuthRepository $authRepository;

    public function __construct(IAuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }
    public function register(AuthRegister $data): BaseModel
    {
        $register = $this->authRepository->register($data);
        return $register;
    }

    public function getUserAuth(AuthRegister $data)
    {
        // Ambil data dari repository
        $result = $this->authRepository->getUserAuth($data);
        if (!$result) {
            throw new \Exception('Invalid username.', 400);
        } else if (!password_verify($data->getPassword(), $result->getPassword())) {
            throw new \Exception('Invalid password.', 400);
        }

        // Konversi peran ke dalam array
        $result->setRoles($result->getRoles());
        $resultToken = $this->getTokenPayload($result);

        if (!$resultToken["token"] || !$resultToken["refresh"]) {
            throw new \Exception('Generate token failed.', 400);
        }
        // Simpan refresh token ke database

        try {
            $result = $this->authRepository->refreshToken($result->getId(), $resultToken["token"], $resultToken["refresh"]);
            return $result;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function revokeToken(string $userId, string $ttl, string $token)
    {
        try {
            $response = $this->authRepository->revokeToken($userId);
            CacheHelper::blacklistToken($token, $ttl);
            return $response;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

    }

    public function refreshToken(string $token)
    {
        $tokenDecode = JwtHelper::decodeExpiredToken($token);
        $response = $this->authRepository->refreshToken($tokenDecode->data->userId);
        return $response;
    }

    public function userContext(Request $request)
    {
        return null;
    }

    private function getTokenPayload(AuthRegister $result): array
    {
        // Payload untuk access token
        $accessTokenPayload = [
            'iss' => Constant::Domain,
            'aud' => Constant::Domain,
            'iat' => time(),
            'exp' => time() + 3600, // Access token berlaku selama 1 jam
            'userId' => $result->getId(),
            'username' => $result->getUserName(),
            'roles' => $result->getRoles(),
        ];

        // Payload untuk refresh token
        $refreshTokenPayload = [
            'iss' => Constant::Domain,
            'aud' => Constant::Domain,
            'iat' => time(),
            'exp' => time() + (7 * 24 * 3600), // Refresh token berlaku selama 7 hari
            'userId' => $result->getId(),
            'username' => $result->getUserName(),
            'roles' => $result->getRoles(),
        ];

        return [
            "token" => JwtHelper::createToken($accessTokenPayload),
            "refresh" => JwtHelper::createToken($refreshTokenPayload),
        ];
    }

}
