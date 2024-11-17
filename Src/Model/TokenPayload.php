<?php

namespace App\Src\Model;

class TokenPayload
{
    // $payload = [
    //     'iss' => 'yourdomain.com', // Issuer: domain yang menerbitkan token
    //     'aud' => 'yourdomain.com', // Audience: domain yang menggunakan token
    //     'iat' => time(), // Issued At: waktu token diterbitkan
    //     'exp' => time() + 3600, // Expiration: token berlaku selama 1 jam
    //     'data' => [ // Data tambahan
    //         'userId' => $userId,
    //         'username' => $username,
    //         'roles' => $roles,
    //     ],
    // ];

    public string $userId;
    public string $name;
    public string $username;
    public string $email;
    public string $password;
    public $roles;

    public function getId(): string
    {
        return $this->userId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUserName(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setId($userId): string
    {
        return $this->userId = $userId;
    }

    public function setName($name): string
    {
        return $this->name = $name;
    }

    public function setUserName($userName): string
    {
        return $this->username = $userName;
    }

    public function setEmail($email): string
    {
        return $this->email = $email;
    }

    public function setPassword($password): string
    {
        return $this->password = $password;
    }

    /**
     * Set the roles and convert to array if necessary
     */
    public function setRoles($roles): array
    {
        if (is_string($roles)) {
            // Assume roles are separated by commas if provided as a string
            $roles = explode(', ', $roles);
        }

        return $this->roles = $roles;
    }

    // Method to populate the model with an array of data using setters
    public function fromArray(array $data): void
    {
        if (isset($data['userId'])) {
            $this->setId($data['userId']);
        }

        if (isset($data['name'])) {
            $this->setName($data['name']);
        }

        if (isset($data['username'])) {
            $this->setUserName($data['username']);
        }

        if (isset($data['email'])) {
            $this->setEmail($data['email']);
        }

        if (isset($data['password'])) {
            $this->setPassword($data['password']);
        }

        if (isset($data['roles'])) {
            $this->setRoles($data['roles']);
        }
    }
}
