<?php

declare (strict_types = 1);

namespace App\Src\Utility\Middleware;

use App\Src\Interface\ILocalStorageService;
use App\Src\Utility\Config\Constant;
use App\Src\Utility\Helper\JsonResponseHelper;
use App\Src\Utility\Helper\JwtHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;
use Slim\Routing\RouteContext;

class JwtMiddleware
{
    private $service;

    // this protectedRoutes depend on routes.php
    private $protectedRoutes = [
        'GET' => [
            'cache' => '/cache',
            'cacheId' => '/cache/',
        ],
        'POST' => [
            'authLogout' => '/auth/logout',
            'category' => '/category',
            'tag' => '/tag',
        ],
        'DELETE' => [
            'category' => '/category',
            'tag' => '/tag',
        ],
    ];

    public function __construct(ILocalStorageService $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $method = $request->getMethod();
        $pattern = $route ? $route->getPattern() : '';

        if ($this->isProtectedRoute($method, $pattern)) {
            $authHeader = $request->getHeaderLine('Authorization');
            if (!$authHeader || strpos($authHeader, 'Bearer ') !== 0) {
                return $this->unauthorizedResponse("Authorization header missing", Constant::TOKEN_MISSING);
            }

            $token = str_replace('Bearer ', '', $authHeader);

            try {
                // Check if token has been blacklisted
                $key = "blacklist_" . hash('sha256', $authHeader);
                if ($this->service->getById($key)) {
                    throw new \Exception("Token has been revoked");
                }

                if (JwtHelper::isTokenExpired($token)) {
                    throw new \Exception("Token has expired");
                }

                // Validate and decode token
                $decodedData = JwtHelper::verifyToken($token);
                $request = $request->withAttribute('userContext', $decodedData);
            } catch (\Throwable $e) {
                return $this->unauthorizedResponse($e->getMessage(), Constant::TOKEN_EXPIRED);
            }
        }

        return $handler->handle($request);
    }
    public function isProtectedRoute(string $method, string $pattern): bool
    {
        $protectedRoutesForMethod = $this->protectedRoutes[$method] ?? [];
        foreach ($protectedRoutesForMethod as $routeName => $routePattern) {
            if (strpos($pattern, $routePattern) === 0) {
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
