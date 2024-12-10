<?php

namespace App\Src\Service;

use App\Src\Interface\IAuthRepository;
use App\Src\Interface\IAuthService;
use App\Src\Interface\ILocalStorageService;
use App\Src\Model\AuthRegister;
use App\Src\Model\BaseModel;
use App\Src\Utility\Config\Constant;
use App\Src\Utility\Helper\JwtHelper;

class AuthService implements IAuthService
{
    private IAuthRepository $authRepository;
    private ILocalStorageService $localStorage;

    public function __construct(IAuthRepository $authRepository, ILocalStorageService $localStorage)
    {
        $this->authRepository = $authRepository;
        $this->localStorage = $localStorage;
    }
    public function register(AuthRegister $data): BaseModel
    {
        $register = $this->authRepository->register($data);
        return $register;
    }

    public function generateToken(string $userId, $roles)
    {
        try {
            $resultToken = $this->getTokenPayload($userId, $roles);

            $result = $this->authRepository->upsertToken($userId, $resultToken["token"], $resultToken["refresh"]);
            if (!$result) {
                throw new \Exception('Generate token failed.', 400);
            }
            return $resultToken["token"];

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function revokeToken(string $userId, string $ttl, string $token)
    {
        try {
            $response = $this->authRepository->revokeToken($userId);
            $key = "blacklist_" . hash('sha256', $token);
            $value = [
                "key" => $key,
                "userId" => $userId,
                "token" => $token,
                "tokenHash" => $token,
                "type" => 'blacklist',
                "response" => $response,
            ];

            return $this->localStorage->set($key, $value, $ttl);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

    }

    public function refreshToken(string $token)
    {
        try {
            $tokenDecode = JwtHelper::decodeExpiredToken($token);
            if (!$tokenDecode->userId) {
                throw new \Exception('Invalid Token', 401);
            }

            $response = $this->authRepository->refreshToken($tokenDecode->userId);

            if (!$response->revokedDate) {
                throw new \Exception('Invalid Token', 401);
            }

            $ValidateTokenExpired = JwtHelper::isRefreshTokenExpired($response->refreshToken);

            return $ValidateTokenExpired;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

    }

    public function getUserById(string $userId)
    {
        return $this->authRepository->getUserAuth($userId);
    }

    private function getTokenPayload(string $userId, $roles): array
    {
        if (is_string($roles)) {
            // Assume roles are separated by commas if provided as a string
            $roles = explode(', ', $roles);
        }

        // Payload untuk access token
        $accessTokenPayload = [
            'iss' => Constant::Domain,
            'aud' => Constant::Domain,
            'iat' => time(),
            'exp' => time() + 3600, // Access token berlaku selama 1 jam
            'userId' => $userId,
            'roles' => $roles,
        ];

        // Payload untuk refresh token
        $refreshTokenPayload = [
            'iss' => Constant::Domain,
            'aud' => Constant::Domain,
            'iat' => time(),
            'exp' => time() + (7 * 24 * 3600), // Refresh token berlaku selama 7 hari
            'userId' => $userId,
            'roles' => $roles,
        ];

        return [
            "token" => JwtHelper::createToken($accessTokenPayload),
            "refresh" => JwtHelper::createToken($refreshTokenPayload),
        ];
    }

}
