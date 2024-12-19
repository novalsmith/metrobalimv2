<?php

namespace App\Src\Service;

use App\Src\Interface\ILocalStorageService;
use App\Src\Utility\Helper\CacheHelper;

class LocalStorageService implements ILocalStorageService
{
    private CacheHelper $cacheHelper;

    public function __construct(CacheHelper $cacheHelper)
    {
        $this->cacheHelper = $cacheHelper;
    }

    public function getAll()
    {
        $staging = [];

        $data = $this->cacheHelper->getAll();
        for ($i = 0; $i < count($data); $i++) {
            $stagingData = [
                "key" => $data[$i]["info"],
                "ttl" => $data[$i]["ttl"],
            ];
            array_push($staging, $stagingData);
        }

        return $staging;
    }

    public function set(string $key, $value, int $ttl = 0)
    {
        return $this->cacheHelper->set($key, $value, $ttl);
    }

    public function getById(string $key)
    {
        return $this->cacheHelper->getById($key);
    }

    public function deleteAll()
    {
        return $this->cacheHelper->clear();

    }
    public function deleteById(string $key)
    {
        return $this->cacheHelper->deleteById($key);

    }

    public function populateMMasterData()
    {
        // todo
        return null;
    }
}
