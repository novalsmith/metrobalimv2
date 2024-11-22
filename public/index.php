<?php

declare (strict_types = 1);

use App\Src\Utility\Handlers\HttpErrorHandler;
use App\Src\Utility\Handlers\ShutdownHandler;
use App\Src\Utility\ResponseEmitter\ResponseEmitter;
use App\Src\Utility\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;

// Menambahkan Dotenv untuk memuat file .env

// use Slim\Psr7\Factory\ServerRequestCreatorFactory;

require __DIR__ . '/../vendor/autoload.php';

/*
Begin sample php APCu cache

if (extension_loaded('apcu')) {
echo "APCu is enabled.<br>";
$user = [];
$user["userId"] = "A12345";
$user["name"] = "Noval Nauw";
$user["email"] = "novalsmith69@gmail.com";

apcu_store('my_key', 'my_value');

$value = apcu_fetch('my_key');

// Menampilkan nilai yang diambil dari cache
if ($value) {
echo "Cache Value: <br>";
echo print_r($value);
apcu_delete('my_key');
echo "success deleted";
$value = apcu_fetch('my_key');
echo print_r($value);

} else {
echo "No data found in cache.<br>";
}
} else {
echo "APCu is not enabled.<br>";
}
return;
// End sample php APCu cache
 */

// Memuat file .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../', '.env.development');
$dotenv->load();

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

if (false) { // Should be set to true in production
    $containerBuilder->enableCompilation(__DIR__ . '/../var/cache');
}

// Set up settings
$settings = require __DIR__ . '/../app/settings.php';
$settings($containerBuilder);

// Set up dependencies
$dependencies = require __DIR__ . '/../app/dependencies.php';
$dependencies($containerBuilder);

// Set up repositories
$repositories = require __DIR__ . '/../app/repositories.php';
$repositories($containerBuilder);

// Build PHP-DI Container instance
$container = $containerBuilder->build();

// Instantiate the app
AppFactory::setContainer($container);
$app = AppFactory::create();
$callableResolver = $app->getCallableResolver();

// Register middleware
$middleware = require __DIR__ . '/../app/middleware.php';
$middleware($app);

// Register routes
$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

/** @var SettingsInterface $settings */
$settings = $container->get(SettingsInterface::class);

$displayErrorDetails = $settings->get('displayErrorDetails');
$logError = $settings->get('logError');
$logErrorDetails = $settings->get('logErrorDetails');

// Create Request object from globals
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

// Create Error Handler
$responseFactory = $app->getResponseFactory();
$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);

// Create Shutdown Handler
$shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
register_shutdown_function($shutdownHandler);

// Add Routing Middleware
$app->addRoutingMiddleware();

// Add Body Parsing Middleware
$app->addBodyParsingMiddleware();

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, $logError, $logErrorDetails);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

// Run App & Emit Response
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);
