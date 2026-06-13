<?php

declare(strict_types=1);

session_start();

spl_autoload_register(function (string $class): void {
    $prefixes = [
        'App\\' => __DIR__ . '/',
        'Core\\' => dirname(__DIR__) . '/core/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
            continue;
        }

        $relative = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relative) . '.php';

        if (is_file($file)) {
            require_once $file;
        }
    }
});

require_once __DIR__ . '/helpers.php';
