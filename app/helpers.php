<?php

declare(strict_types=1);

function config(string $key, mixed $default = null): mixed
{
    static $config = null;

    if ($config === null) {
        $config = require dirname(__DIR__) . '/config/app.php';
    }

    $segments = explode('.', $key);
    $value = $config;

    foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }

        $value = $value[$segment];
    }

    return $value;
}

function e(string|null $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function base_url(string $path = ''): string
{
    $scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');

    if (str_ends_with($scriptDir, '/public')) {
        $scriptDir = substr($scriptDir, 0, -7);
    }

    $base = $scriptDir === '/' ? '' : $scriptDir;
    $path = '/' . ltrim($path, '/');

    return $base . $path;
}
