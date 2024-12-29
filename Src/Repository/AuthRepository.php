<?php

namespace App\Src\Repository;

use App\Src\Interface\IAuthRepository;
use App\Src\Model\AuthRegister;
use App\Src\Model\BaseModel;
use App\Src\Utility\Config\Constant;

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

        return $this->executeQueryFetchObject(Constant::SPAuth_CreateUser, $params, BaseModel::class);
    }

    public function getUserById(string $userId)
    {
        $param = [
            $userId,
        ];
        $result = $this->executeQueryFetchObject(Constant::SPAuth_GetUserById, $param, AuthRegister::class);

        return $result;
    }

    public function validateEmail(string $email)
    {
        $param = [
            $email,
        ];
        $result = $this->executeQueryFetchObject(Constant::SPAuth_GetUserAuthCredential, $param, AuthRegister::class);

        return $result;
    }

    public function refreshToken(string $userId)
    {
        $param = [
            $userId,
        ];
        $response = $this->executeQueryFetchObject(Constant::SPAuth_GetUserAuthToken, $param);
        return $response;
    }

    public function upsertToken(string $userId, string $token = null, string $refresh = null)
    {
        $param = [
            $userId,
            $token,
            $refresh,
        ];
        $response = $this->executeQueryFetchObject(Constant::SPAuth_UpsertAuthToken, $param);
        return $response;
    }

    public function revokeToken(string $userId)
    {
        $params = [$userId];
        $response = $this->executeQueryFetchObject(Constant::SPAuth_RevokeAuthToken, $params);
        return $response;
    }
}
