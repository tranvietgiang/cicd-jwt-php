<?php

declare(strict_types=1);

namespace Core;

use PDO;

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection !== null) {
            return self::$connection;
        }

        $host = config('database.host');
        $port = config('database.port');
        $name = config('database.name');
        $charset = config('database.charset');
        $dsn = "mysql:host={$host};port={$port};dbname={$name};charset={$charset}";

        self::$connection = new PDO($dsn, config('database.user'), config('database.password'), [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        return self::$connection;
    }
}
