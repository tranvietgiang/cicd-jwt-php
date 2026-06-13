<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use Core\Exceptions\HttpException;
use Core\Request;
use Core\Response;

final class UserController
{
    public function me(Request $request, Response $response): void
    {
        $payload = $request->user();
        $user = User::find((int) ($payload['sub'] ?? 0));

        if ($user === null) {
            throw new HttpException('User not found', 404);
        }

        unset($user['password']);

        $response->json([
            'data' => $user,
        ]);
    }
}
