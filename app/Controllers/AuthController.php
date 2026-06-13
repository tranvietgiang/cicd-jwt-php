<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use Core\Exceptions\HttpException;
use Core\Request;
use Core\Response;
use Core\Security\Jwt;
use Core\Validator;

final class AuthController
{
    public function register(Request $request, Response $response): void
    {
        $data = $request->input();
        $errors = Validator::required($data, ['name', 'email', 'password']);

        if (!filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
            $errors['email'][] = 'Email khong hop le.';
        }

        if (strlen((string) ($data['password'] ?? '')) < 8) {
            $errors['password'][] = 'Mat khau toi thieu 8 ky tu.';
        }

        if ($errors !== []) {
            $response->json(['errors' => $errors], 422);
            return;
        }

        if (User::findByEmail((string) $data['email']) !== null) {
            $response->json(['errors' => ['email' => ['Email da ton tai.']]], 422);
            return;
        }

        $userId = User::create([
            'name' => trim((string) $data['name']),
            'email' => strtolower(trim((string) $data['email'])),
            'password' => password_hash((string) $data['password'], PASSWORD_DEFAULT),
        ]);

        $response->json([
            'message' => 'Dang ky thanh cong.',
            'token' => Jwt::encode(['sub' => $userId, 'email' => strtolower((string) $data['email'])]),
        ], 201);
    }

    public function login(Request $request, Response $response): void
    {
        $data = $request->input();
        $errors = Validator::required($data, ['email', 'password']);

        if ($errors !== []) {
            $response->json(['errors' => $errors], 422);
            return;
        }

        $user = User::findByEmail((string) $data['email']);

        if ($user === null || !password_verify((string) $data['password'], $user['password'])) {
            throw new HttpException('Email hoac mat khau khong dung', 401);
        }

        $response->json([
            'message' => 'Dang nhap thanh cong.',
            'token' => Jwt::encode(['sub' => (int) $user['id'], 'email' => $user['email']]),
        ]);
    }
}
