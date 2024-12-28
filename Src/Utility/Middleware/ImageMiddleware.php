<?php

declare (strict_types = 1);

namespace App\Src\Utility\Middleware;

use App\Src\Interface\ILocalStorageService;
use App\Src\Utility\Config\Constant;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;

class ImageMiddleware
{
    private $service;

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

        // Tangani path gambar dengan tahun/bulan
        $imagePathPrefix = Constant::ImagePath . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . date('m') . DIRECTORY_SEPARATOR;
        $thumbnailPathPrefix = Constant::ImageThumbnailPath . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . date('m') . DIRECTORY_SEPARATOR;

        // Create folder if not exist
        if (!is_dir($imagePathPrefix)) {
            mkdir($imagePathPrefix, 0777, true);
        }

        if (!is_dir($thumbnailPathPrefix)) {
            mkdir($thumbnailPathPrefix, 0777, true);
        }

        // Handle the request
        $response = $handler->handle($request);

        // Add cache headers if the route is for serving images
        if (str_starts_with($pattern, Constant::ImagePath)) {
            $imagePath = Constant::ImagePath . parse_url($request->getUri()->getPath(), PHP_URL_PATH);

            // Check if the image exists
            if (file_exists($imagePath)) {
                $fileTime = filemtime($imagePath);
                $etag = md5((string) $fileTime); // Create ETag based on file modification time
                $lastModified = gmdate('D, d M Y H:i:s', $fileTime) . ' GMT';

                // Check for If-Modified-Since or If-None-Match headers to handle conditional requests
                $ifModifiedSince = $request->getHeaderLine('If-Modified-Since');
                $ifNoneMatch = $request->getHeaderLine('If-None-Match');

                // If the file hasn't changed, respond with 304 Not Modified
                if (($ifModifiedSince && strtotime($ifModifiedSince) >= $fileTime) || ($ifNoneMatch && $ifNoneMatch === $etag)) {
                    return $response->withStatus(304); // Not Modified
                }

                // Set cache headers
                return $response
                    ->withHeader('Cache-Control', 'public, max-age=31536000') // Cache for 1 year
                    ->withHeader('Expires', gmdate('D, d M Y H:i:s', strtotime('+1 year')) . ' GMT') // Expiry set to 1 year
                    ->withHeader('Last-Modified', $lastModified) // Last modified time
                    ->withHeader('ETag', $etag); // ETag for conditional requests
            }
        }

        return $response;
    }
}
