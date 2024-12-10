<?php

namespace App\Src\Model;

class UserContext
{
    private string $userId;
    private array $roles;

    public function setUserId(string $userId): string
    {
        return $this->userId = $userId;
    }

    public function setRoles(array $role): array
    {
        return $this->roles = $role;
    }
    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles, true);
    }
}
