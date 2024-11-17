<?php

namespace App\Src\Utility\Helper;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHelper
{
    // Gantilah 'your_secret_key' dengan kunci rahasia yang aman
    private static $secretKey = 'your_secret_key';

    /**
     * Fungsi untuk membuat token JWT
     *
     * @param int $userId - ID pengguna
     * @param string $username - Nama pengguna
     * @return string - Token JWT yang dihasilkan
     */
    public static function createToken(array $payload): string
    {
        return JWT::encode($payload, self::$secretKey, 'HS256');
        // $data["token"] = $token;
        // $data["decode"] = JWT::decode($token, new Key(self::$secretKey, 'HS256'));

        // return $data;

    }

    /**
     * Fungsi untuk memverifikasi token JWT
     *
     * @param string $token - Token JWT yang diterima
     * @return array|null - Data pengguna jika token valid, atau null jika tidak valid
     */
    public static function verifyToken(string $token)
    {
        try {
            // Decode token dan dapatkan objek yang didecode
            $decoded = JWT::decode($token, new Key(self::$secretKey, 'HS256'));

            // Pastikan `data` ada di dalam token yang di-decode
            if (isset($decoded->data)) {
                return $decoded->data;
            }

            return null;
        } catch (\Exception $e) {
            // Jika terjadi error, log exception dan return null
            error_log("JWT verification error: " . $e->getMessage());
            return null;
        }
    }
}
