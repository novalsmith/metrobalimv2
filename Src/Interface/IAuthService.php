<?php

namespace App\Src\Interface;

use App\Src\Model\AuthRegister;
use App\Src\Model\BaseModel;

interface IAuthService
{
    public function register(AuthRegister $data): BaseModel;
    public function generateToken(string $userId, $roles);
    public function revokeToken(string $userId, string $ttl, string $token);
    public function refreshToken(string $userId);
    public function getUserById(string $userId);
    public function validateEmail(string $email);
}
