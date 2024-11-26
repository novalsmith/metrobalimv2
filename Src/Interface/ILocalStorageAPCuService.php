<?php

namespace App\Src\Interface;

interface ILocalStorageAPCuService
{

    public function getAll();
    public function getById(string $userId);
    public function deleteById(string $userId);
}
