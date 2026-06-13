<?php

declare(strict_types=1);

namespace Core\Middleware;

use Core\Exceptions\HttpException;
use Core\Request;
use Core\Security\Jwt;

final class AuthMiddleware
{
    public function handle(Request $request): void
    {
        $token = $request->bearerToken();

        if ($token === null) {
            throw new HttpException('Missing bearer token', 401);
        }

        $payload = Jwt::decode($token);
        $request->setUser($payload);
    }
}
