<?php

namespace App\Src\Utility\Helper;

class CacheHelper
{
    // Metode lain tetap sama...

    /**
     * Tambahkan token ke dalam daftar blacklist.
     *
     * @param string $token
     * @param int $ttl Waktu token tetap berada di cache (detik)
     * @return bool
     */
    public static function blacklistToken(string $token, int $ttl): bool
    {
        if (!function_exists('apcu_store')) {
            throw new \Exception("APCu is not enabled.");
        }

        // Gunakan hash dari token sebagai key untuk keamanan tambahan
        $key = "blacklist_" . hash('sha256', $token);

        return apcu_store($key, true, $ttl);
    }

    /**
     * Periksa apakah token ada di blacklist.
     *
     * @param string $token
     * @return bool
     */
    public static function isTokenBlacklisted(string $token): bool
    {
        if (!function_exists('apcu_exists')) {
            throw new \Exception("APCu is not enabled.");
        }

        $key = "blacklist_" . hash('sha256', $token);

        return apcu_exists($key);
    }
}
