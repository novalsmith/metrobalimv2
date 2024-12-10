<?php

namespace App\Src\Utility\Helper;

class CacheHelper
{
    public static function set(string $key, $value, int $ttl = 0): bool
    {
        if (!function_exists('apcu_store')) {
            throw new \Exception("APCu is not enabled.");
        }

        return apcu_store($key, $value, $ttl);
    }

    public static function getById(string $key): mixed
    {
        if (!function_exists('apcu_fetch')) {
            throw new \Exception("APCu is not enabled.");
        }

        return apcu_fetch($key);
    }

    public static function deleteById(string $key): bool
    {
        if (!function_exists('apcu_delete')) {
            throw new \Exception("APCu is not enabled.");
        }

        return apcu_delete($key);
    }

    public static function exists(string $key): bool
    {
        if (!function_exists('apcu_exists')) {
            throw new \Exception("APCu is not enabled.");
        }

        return apcu_exists($key);
    }

    public static function clear(): bool
    {
        if (!function_exists('apcu_clear_cache')) {
            throw new \Exception("APCu is not enabled.");
        }

        return apcu_clear_cache();
    }

    public static function getAll()
    {
        if (!function_exists('apcu_cache_info')) {
            throw new \Exception("APCu is not enabled.");
        }

        $info = apcu_cache_info();
        return $info['cache_list'] ?? [];
    }
}
