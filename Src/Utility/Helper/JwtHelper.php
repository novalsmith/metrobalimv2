<?php

namespace App\Src\Utility\Helper;

use App\Src\Utility\Config\Constant;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHelper
{
    private static $secretKey = Constant::Secret_Key;

    /**
     * Fungsi untuk membuat token JWT
     *
     * @param array $payload - Payload untuk token
     * @return string - Token JWT yang dihasilkan
     */
    public static function createToken(array $payload): string
    {
        return JWT::encode($payload, self::$secretKey, 'HS256');
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
            $decoded = JWT::decode($token, new Key(self::$secretKey, 'HS256'));
            return $decoded;
        } catch (\Firebase\JWT\ExpiredException $e) {
            // Token expired
            error_log("JWT expired error: " . $e->getMessage());
            return $e->getMessage();
        } catch (\Exception $e) {
            // Error lainnya
            error_log("JWT verification error: " . $e->getMessage());
            return $e->getMessage();
        }
    }

    /**
     * Fungsi untuk memeriksa apakah token sudah expired
     *
     * @param string $token - Token JWT yang diterima
     * @return bool - True jika token expired, false jika masih valid
     */
    public static function isTokenExpired(string $token): bool
    {
        try {
            // Decode token tanpa memverifikasi signature (untuk hanya mendapatkan payload)
            $decoded = JWT::decode($token, new Key(self::$secretKey, 'HS256'));

            // Cek apakah expired (field 'exp' ada dalam token)
            if (isset($decoded->exp) && $decoded->exp < time()) {
                return true;
            }

            return false;
        } catch (\Exception $e) {
            // Jika error terjadi, anggap token invalid
            return true;
        }
    }

    public static function isRefreshTokenExpired(string $token)
    {
        try {
            // Decode token tanpa memverifikasi signature (untuk hanya mendapatkan payload)
            $token = str_replace('Bearer ', '', $token);
            $decoded = JWT::decode($token, new Key(self::$secretKey, 'HS256'));

            return $decoded;
        } catch (\Exception $e) {
            // Jika error terjadi, anggap token invalid
            return true;
        }
    }

    public static function decodeExpiredToken(string $token)
    {
        try {
            // Pisahkan token menjadi header, payload, dan signature
            [$header, $payload, $signature] = explode('.', $token);

            // Decode payload tanpa memverifikasi
            $decodedPayload = json_decode(base64_decode($payload));

            return $decodedPayload; // Hanya ambil data payload
        } catch (\Exception $e) {
            // Log error jika token tidak valid
            error_log("Error decoding token: " . $e->getMessage());
            return null;
        }
    }

    public function generateSecretKey(): string
    {
        // Random string (16 karakter)
        $randomString = bin2hex(random_bytes(8));

        // Kombinasi tanggal
        $dateComponent = date('Ymd'); // Format: YYYYMMDD

        // Random angka (4 digit)
        $randomNumber = rand(1000, 9999);

        // Gabungkan semua komponen
        return hash('sha256', $randomString . $dateComponent . $randomNumber);
    }

}
