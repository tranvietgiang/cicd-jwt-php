<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Request;
use Core\Response;
use Core\Security\Csrf;

final class CsrfController
{
    public function show(Request $request, Response $response): void
    {
        $response->json([
            'csrf_token' => Csrf::token(),
        ]);
    }
}
