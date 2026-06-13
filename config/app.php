<?php

declare(strict_types=1);

return [
    'app' => [
        'name' => 'Pure PHP MVC JWT API',
        'debug' => true,
    ],
    'database' => [
        'host' => '127.0.0.1',
        'port' => '3306',
        'name' => 'cicd_jwt_php',
        'user' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
    ],
    'jwt' => [
        'secret' => 'CHANGE_ME_TO_A_LONG_RANDOM_SECRET',
        'issuer' => 'cicd-jwt-php',
        'ttl' => 3600,
    ],
    'cors' => [
        'origin' => 'http://localhost',
    ],
];
