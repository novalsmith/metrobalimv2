<?php

namespace App\Src\Interface;

use App\Src\Model\AuthRegister;
use App\Src\Model\BaseModel;

interface IAuthService
{
    public function register(AuthRegister $data): BaseModel;
    public function getUserAuth(AuthRegister $data): BaseModel;
    public function revokeToken(string $userId): BaseModel;
}
