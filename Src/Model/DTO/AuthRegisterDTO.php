<?php

namespace App\Src\Model\DTO;

class AuthRegisterDTO
{
    public string $userId;
    public string $name;
    public string $username;
    public string $email;
    public string $password;
    public array $roles;
    public function __construct(string $userId, string $name, string $username, string $email, string $password, string $roles)
    {
        $this->userId = $userId;
        $this->name = $name;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->roles = $roles;
    }

}
