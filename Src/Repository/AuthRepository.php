<?php

namespace App\Src\Repository;

use App\Src\Interface\IAuthRepository;
use App\Src\Model\AuthRegister;
use App\Src\Model\BaseModel;

class AuthRepository extends BaseRepository implements IAuthRepository
{

    public function register(AuthRegister $data): BaseModel
    {
        $params = [
            $data->getName(),
            $data->getEmail(),
            $data->getUserName(),
            password_hash($data->getPassword(), PASSWORD_BCRYPT),
        ];

        return $this->executeQueryFetchObject("CreateUser", $params, BaseModel::class);
    }

    public function getUserAuth(AuthRegister $data)
    {
        $param = [
            $data->getUserName(),
        ];
        $result = $this->executeQueryFetchObject("GetUserAuthCredential", $param, AuthRegister::class);

        return $result;
    }

    public function refreshToken(string $userId, string $token, string $refreshToken): BaseModel
    {
        $param = [
            $userId,
            $token,
            $refreshToken,
        ];
        $result = $this->executeQueryFetchObject("UpsertAuthToken", $param, BaseModel::class);

        return $result;
    }

    public function revokeToken(string $userId): BaseModel
    {
        $params = [$userId];
        // Ambil data dari repository
        $response = $this->executeQueryFetchObject("RevokeAuthToken", $params);
        $data = new BaseModel;
        $data->setMessage($response["message"]);
        $data->setStatus($response["status"]);
        return $data;
    }
}
