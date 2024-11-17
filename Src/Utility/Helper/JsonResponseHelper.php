<?php

namespace App\Src\Utility\Helper;

use Psr\Http\Message\ResponseInterface as Response;

class JsonResponseHelper
{
    public static function respondWithData(Response $response, $data = null, int $statusCode = 200): Response
    {
        $payload = [
            'statusCode' => $statusCode,
            'data' => $data,
        ];

        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $response->getBody()->write($json);

        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }

    public static function respondWithHeader(Response $response): Response
    {
        // Menambahkan header tambahan jika perlu
        return $response->withHeader('X-Custom-Header', 'Value');
    }
}
