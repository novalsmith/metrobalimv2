<?php

namespace App\Src\Model;

class AuthRegister
{
    private string $userId;
    private string $name;
    private string $email;
    private string $password;
    private array $roles;

    // Konstruktor untuk menginisialisasi properti menggunakan array
    public function __construct(array $data)
    {
        $this->userId = $data['userId'] ?? ''; // Default ke string kosong jika tidak ada
        $this->name = $data['name'] ?? ''; // Default ke string kosong jika tidak ada
        $this->email = $data['email'] ?? ''; // Default ke string kosong jika tidak ada
        $this->password = $data['password'] ?? ''; // Default ke string kosong jika tidak ada
        $this->roles = $this->setRoles($data['roles'] ?? ["user"]); // Default ke array kosong jika tidak ada
    }

    // Getter untuk setiap properti
    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles($roles): array
    {
        if (is_string($roles)) {
            // Assume roles are separated by commas if provided as a string
            $roles = explode(', ', $roles);
        }

        return $this->roles = $roles;
    }
}
