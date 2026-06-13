<?php

declare(strict_types=1);

namespace App\Models;

use Core\Database;

final class User
{
    public static function create(array $data): int
    {
        $statement = Database::connection()->prepare(
            'INSERT INTO users (name, email, password) VALUES (:name, :email, :password)'
        );
        $statement->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        return (int) Database::connection()->lastInsertId();
    }

    public static function find(int $id): ?array
    {
        $statement = Database::connection()->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $user = $statement->fetch();

        return $user ?: null;
    }

    public static function findByEmail(string $email): ?array
    {
        $statement = Database::connection()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $statement->execute(['email' => strtolower(trim($email))]);
        $user = $statement->fetch();

        return $user ?: null;
    }
}
