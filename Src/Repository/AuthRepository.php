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
            $data->getUserId(),
            password_hash($data->getPassword(), PASSWORD_BCRYPT),
        ];

        return $this->executeQueryFetchObject("CreateUser", $params, BaseModel::class);
    }

    public function getUserById(string $userId)
    {
        $param = [
            $userId,
        ];
        $result = $this->executeQueryFetchObject("GetUserById", $param, AuthRegister::class);

        return $result;
    }

    public function validateEmail(string $email)
    {
        $param = [
            $email,
        ];
        $result = $this->executeQueryFetchObject("GetUserAuthCredential", $param, AuthRegister::class);

        return $result;
    }

    public function refreshToken(string $userId)
    {
        $param = [
            $userId,
        ];
        $response = $this->executeQueryFetchObject("GetUserAuthToken", $param);
        return $response;
    }

    public function upsertToken(string $userId, string $token = null, string $refresh = null)
    {
        $param = [
            $userId,
            $token,
            $refresh,
        ];
        $response = $this->executeQueryFetchObject("UpsertAuthToken", $param);
        return $response;
    }

    public function revokeToken(string $userId)
    {
        $params = [$userId];
        $response = $this->executeQueryFetchObject("RevokeAuthToken", $params);
        return $response;
    }
}
