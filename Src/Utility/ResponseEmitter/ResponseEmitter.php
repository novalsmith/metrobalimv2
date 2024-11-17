<?php

declare (strict_types = 1);

namespace App\Src\Utility\ResponseEmitter;

use Psr\Http\Message\ResponseInterface;
use Slim\ResponseEmitter as SlimResponseEmitter;

class ResponseEmitter extends SlimResponseEmitter
{
    /**
     * {@inheritdoc}
     */
    public function emit(ResponseInterface $response): void
    {
        // Ini adalah variabel untuk menentukan host yang diizinkan untuk mengakses API
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

        // Menambahkan headers keamanan dan kinerja
        $response = $response
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withHeader('Access-Control-Allow-Origin', $origin)
            ->withHeader(
                'Access-Control-Allow-Headers',
                'X-Requested-With, Content-Type, Accept, Origin, Authorization'
            )
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
            ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->withAddedHeader('Cache-Control', 'post-check=0, pre-check=0')
            ->withHeader('Pragma', 'no-cache')
            ->withHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload')
            ->withHeader('Content-Security-Policy', "default-src 'self'")
            ->withHeader('X-Content-Type-Options', 'nosniff')
            ->withHeader('X-Frame-Options', 'DENY')
            ->withHeader('Referrer-Policy', 'no-referrer');

        // Membersihkan buffer output jika ada
        if (ob_get_contents()) {
            ob_clean();
        }

        // Mengatur respons dengan kompresi GZIP jika diperlukan
        if (extension_loaded('zlib') && ob_get_level() === 0) {
            ob_start('ob_gzhandler');
            $response = $response->withHeader('Content-Encoding', 'gzip');
        }

        // Emit respons
        parent::emit($response);
    }
}
