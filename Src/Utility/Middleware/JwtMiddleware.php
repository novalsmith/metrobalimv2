<?php

declare (strict_types = 1);

namespace App\Src\Utility\Middleware;

use App\Src\Utility\Helper\JwtHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;
use Slim\Routing\RouteContext;

class JwtMiddleware
{
    // Daftar endpoint yang perlu divalidasi JWT
    private $protectedRoutes = [
        'GET' => ['/categories', '/categories/{id}'],
        'POST' => ['/categories'],
        'POST' => ['/auth/logout'],
        // Tambahkan endpoint lain yang perlu di-protect di sini
    ];

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        // Dapatkan metode dan rute yang diminta
        $method = $request->getMethod();
        $pattern = $route ? $route->getPattern() : '';
        $authHeader = $request->getHeaderLine('Authorization');
        // return $this->unauthorizedResponse($pattern);

        // Cek apakah rute saat ini perlu validasi JWT
        if (isset($this->protectedRoutes[$method]) && in_array($pattern, $this->protectedRoutes[$method])) {
            // var_dump($authHeader);
            // return $authHeader;

            if (!$authHeader || strpos($authHeader, 'Bearer ') !== 0) {
                return $this->unauthorizedResponse($authHeader);
            }

            $token = str_replace('Bearer ', '', $authHeader);

            // Verifikasi token menggunakan JwtHelper
            $decodedData = JwtHelper::verifyToken($token);
            if (!$decodedData) {
                return $this->unauthorizedResponse($decodedData);
            }

            // Tambahkan data token ke dalam request agar bisa digunakan di endpoint
            $request = $request->withAttribute('userData', $decodedData);
        }

        // Lanjutkan ke handler berikutnya jika token valid atau tidak perlu divalidasi
        return $handler->handle($request);
    }

    private function unauthorizedResponse($authHeader): Response
    {
        $response = new SlimResponse();
        $response->getBody()->write(json_encode($authHeader));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }
}
