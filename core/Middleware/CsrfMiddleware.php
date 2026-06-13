<?php

declare(strict_types=1);

namespace Core\Middleware;

use Core\Exceptions\HttpException;
use Core\Request;
use Core\Security\Csrf;

final class CsrfMiddleware
{
    public function handle(Request $request): void
    {
        if (in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)) {
            return;
        }

        $token = $request->header('X-CSRF-Token') ?? ($request->input()['_csrf_token'] ?? null);

        if (!Csrf::verify($token)) {
            throw new HttpException('Invalid CSRF token', 419);
        }
    }
}
