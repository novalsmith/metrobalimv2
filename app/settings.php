<?php

declare (strict_types = 1);

use App\Src\Utility\Settings\Settings;
use App\Src\Utility\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => $_ENV['APP_DEBUG'] === 'true',
                'logError' => false,
                'logErrorDetails' => false,
                'logger' => [
                    'name' => 'slim-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
                'db' => [
                    'driver' => $_ENV['DB_DRIVER'],
                    'host' => $_ENV['DB_HOST'],
                    'database' => $_ENV['DB_NAME'],
                    'username' => $_ENV['DB_USER'],
                    'password' => $_ENV['DB_PASS'],
                    'charset' => $_ENV['DB_CHARSET'],
                ],
            ]);
        },
        // Definisi koneksi PDO
        PDO::class => function ($container) {
            $settings = $container->get(SettingsInterface::class)->get('db');
            $dsn = sprintf(
                '%s:host=%s;dbname=%s;charset=%s',
                $settings['driver'],
                $settings['host'],
                $settings['database'],
                $settings['charset']
            );
            try {
                return new PDO($dsn, $settings['username'], $settings['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                die('Connection failed: ' . $e->getMessage());
            }
        },
    ]);
};
