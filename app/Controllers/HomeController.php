<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Request;
use Core\Response;

final class HomeController
{
    public function index(Request $request, Response $response): void
    {
        ob_start();
        require dirname(__DIR__) . '/Views/home.php';
        $response->html((string) ob_get_clean());
    }
}
