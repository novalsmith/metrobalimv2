<?php

declare (strict_types = 1);

namespace App\Src\Utility\Middleware;

use App\Src\Utility\Config\Constant;
use App\Src\Utility\Helper\CacheHelper;
use App\Src\Utility\Helper\JsonResponseHelper;
use App\Src\Utility\Helper\JwtHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;
use Slim\Routing\RouteContext;

class JwtMiddleware
{
    private $protectedRoutes = [
        'GET' => ['/^\/categories(\/\d+)?$/'],
        'POST' => ['/^\/categories$/', '/^\/auth\/logout$/'],
    ];

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $method = $request->getMethod();
        $pattern = $route ? $route->getPattern() : '';

        // Periksa apakah rute dilindungi
        if ($this->isProtectedRoute($method, $pattern)) {
            $authHeader = $request->getHeaderLine('Authorization');
            if (!$authHeader || strpos($authHeader, 'Bearer ') !== 0) {
                return $this->unauthorizedResponse("Authorization header missing", Constant::TOKEN_MISSING);
            }

            $token = str_replace('Bearer ', '', $authHeader);

            try {
                // Validasi dan decode token
                $decodedData = JwtHelper::verifyToken($token);

                // Cek apakah token telah diblacklist
                if (CacheHelper::isTokenBlacklisted($authHeader)) {
                    throw new \Exception("Token has been revoked");
                }

                if (JwtHelper::isTokenExpired($token)) {
                    throw new \Exception("Token has expired");
                }
                $request = $request->withAttribute('userData', $decodedData);
            } catch (\Throwable $e) {
                return $this->unauthorizedResponse($e->getMessage(), Constant::TOKEN_EXPIRED);
            }
        }

        return $handler->handle($request);
    }

    private function isProtectedRoute(string $method, string $pattern): bool
    {
        foreach ($this->protectedRoutes[$method] ?? [] as $protectedPattern) {
            if (preg_match($protectedPattern, $pattern)) {
                return true;
            }
        }
        return false;
    }

    private function unauthorizedResponse(string $message, string $errorCode): Response
    {
        $response = new SlimResponse();
        return JsonResponseHelper::respondWithError($response, $message, 401, $errorCode);
    }
}
