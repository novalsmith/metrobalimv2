<?php

namespace App\Src\Interface;

interface ILocalStorageService
{

    public function getAll();
    public function set(string $key, $value, int $ttl = 0);
    public function getById(string $key);
    public function deleteAll();
    public function deleteById(string $key);
}
