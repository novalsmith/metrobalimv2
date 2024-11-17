<?php

namespace App\Src\Controller;

use App\Src\Utility\Actions\ActionPayload;
use Psr\Http\Message\ResponseInterface as Response;

class BaseController
{
    protected function respondWithData(Response $response, $data = null, int $statusCode = 200): Response
    {
        $payload = new ActionPayload($statusCode, $data);
        return $this->respond($response, $payload);
    }

    protected function respond(Response $response, ActionPayload $payload): Response
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $response->getBody()->write($json);

        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus($payload->getStatusCode());
    }
}
