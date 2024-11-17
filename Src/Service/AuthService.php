<?php

namespace App\Src\Service;

use App\Src\Interface\IAuthRepository;
use App\Src\Interface\IAuthService;
use App\Src\Model\AuthRegister;
use App\Src\Model\BaseModel;
use App\Src\Utility\Config\Constant;
use App\Src\Utility\Helper\JwtHelper;

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

    public function getUserAuth(AuthRegister $data): BaseModel
    {
        $res = new BaseModel;
        // Ambil data dari repository
        $result = $this->authRepository->getUserAuth($data);
        if (!$result) {
            $res->setMessage("Akun tidak ditemukan");
            $res->setStatus(Constant::ERROR_STATUS);
            return $res;
        } else if (!password_verify($data->getPassword(), $result->getPassword())) {
            $res->setMessage("Password salah");
            $res->setStatus(Constant::ERROR_STATUS);
            return $res;
        }

        // Konversi peran ke dalam array
        $result->setRoles($result->getRoles());

        // Payload untuk access token
        $accessTokenPayload = [
            'iss' => 'metrobalim.com',
            'aud' => 'metrobalim.com',
            'iat' => time(),
            'exp' => time() + 3600, // Access token berlaku selama 1 jam
            'data' => [
                'userId' => $result->getId(),
                'username' => $result->getUserName(),
                'roles' => $result->getRoles(),
            ],
        ];

        // Payload untuk refresh token
        $refreshTokenPayload = [
            'iss' => 'metrobalim.com',
            'aud' => 'metrobalim.com',
            'iat' => time(),
            'exp' => time() + (7 * 24 * 3600), // Refresh token berlaku selama 7 hari
            'data' => [
                'userId' => $result->getId(),
                'username' => $result->getUserName(),
            ],
        ];

        // Buat access token dan refresh token
        $accessToken = JwtHelper::createToken($accessTokenPayload);
        $refreshToken = JwtHelper::createToken($refreshTokenPayload);

        // Simpan refresh token ke database

        $result = $this->authRepository->refreshToken($result->getId(), $accessToken, $refreshToken);
        if (!$result) {
            $res->setMessage("Gagal generate token");
            $res->setStatus(Constant::ERROR_STATUS);
            return $res;
        }

        // Kirimkan token dalam respons
        $res->setMessage("Berhasil login dan generate token");
        $res->setStatus(Constant::SUCCESS_STATUS);
        $res->setData(['token' => $accessToken]);

        return $res;
    }

    public function revokeToken(string $userId): BaseModel
    {
        // Ambil data dari repository
        $response = $this->authRepository->revokeToken($userId);
        // $data = new BaseModel;
        // $data->setMessage($response["message"]);
        // $data->setStatus($response["status"]);

        return $response;
    }

}
