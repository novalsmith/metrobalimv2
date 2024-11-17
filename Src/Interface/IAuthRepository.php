<?php

namespace App\Src\Interface;

use App\Src\Model\AuthRegister;
use App\Src\Model\BaseModel;

interface IAuthRepository
{
    public function register(AuthRegister $data): BaseModel;
    public function getUserAuth(AuthRegister $data);
    public function revokeToken(string $userId): BaseModel;
    public function refreshToken(string $userId, string $token, string $refreshToken): BaseModel;
}
