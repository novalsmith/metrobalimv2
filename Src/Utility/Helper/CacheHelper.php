<?php

namespace App\Src\Utility\Helpers;

class CacheHelper
{
    /**
     * Menyimpan data ke dalam cache.
     *
     * @param string $key
     * @param mixed $value
     * @param int|null $ttl
     * @return bool
     */
    public static function store(string $key, $value, ?int $ttl = null): bool
    {
        if (!function_exists('apcu_store')) {
            throw new \Exception("APCu is not enabled.");
        }

        return apcu_store($key, $value, $ttl);
    }

    /**
     * Mengambil data dari cache berdasarkan key.
     *
     * @param string $key
     * @return mixed|null
     */
    public static function fetch(string $key)
    {
        if (!function_exists('apcu_fetch')) {
            throw new \Exception("APCu is not enabled.");
        }

        $success = false;
        $data = apcu_fetch($key, $success);

        return $success ? $data : null;
    }

    /**
     * Menghapus cache berdasarkan key.
     *
     * @param string $key
     * @return bool
     */
    public static function delete(string $key): bool
    {
        if (!function_exists('apcu_delete')) {
            throw new \Exception("APCu is not enabled.");
        }

        return apcu_delete($key);
    }

    /**
     * Membersihkan seluruh cache.
     *
     * @return bool
     */
    public static function clearAll(): bool
    {
        if (!function_exists('apcu_clear_cache')) {
            throw new \Exception("APCu is not enabled.");
        }

        apcu_clear_cache();
        return true;
    }

    /**
     * Mengecek apakah key ada di dalam cache.
     *
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        if (!function_exists('apcu_exists')) {
            throw new \Exception("APCu is not enabled.");
        }

        return apcu_exists($key);
    }
}
