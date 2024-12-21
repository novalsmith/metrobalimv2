<?php

namespace App\Src\Utility\Middleware;

use App\Src\Utility\Helper\JsonResponseHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Factory\ResponseFactory;

class ValidationMiddleware
{
    private string $modelClass;

    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $data = json_decode($request->getBody()->getContents(), true);

        try {
            // Panggil metode validasi statis dari model
            $this->modelClass::validate($data);
        } catch (\InvalidArgumentException $e) {
            $responseFactory = new ResponseFactory();
            $response = $responseFactory->createResponse();
            return JsonResponseHelper::respondWithData(
                $response,
                ['status' => 'error', 'errors' => json_decode($e->getMessage(), true)],
                400
            );
        }

        return $handler->handle($request);
    }
}
