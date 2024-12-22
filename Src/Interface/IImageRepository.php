<?php

namespace App\Src\Interface;

use App\Src\Model\AuthRegister;
use App\Src\Model\BaseModel;

interface IAuthRepository
{
    public function register(AuthRegister $data): BaseModel;
    public function getUserById(string $userId);
    public function revokeToken(string $userId);
    public function refreshToken(string $userId);
    public function upsertToken(string $userId, string $token = null, string $refresh = null);
    public function validateEmail(string $email);
}
