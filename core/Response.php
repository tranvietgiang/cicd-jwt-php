<?php

declare(strict_types=1);

namespace Core;

final class Response
{
    public function json(array $data, int $status = 200): void
    {
        $this->sendCorsHeaders();
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function html(string $html, int $status = 200): void
    {
        $this->sendCorsHeaders();
        http_response_code($status);
        header('Content-Type: text/html; charset=utf-8');
        echo $html;
    }

    private function sendCorsHeaders(): void
    {
        header('Access-Control-Allow-Origin: ' . config('cors.origin', 'http://localhost'));
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-CSRF-Token');
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
    }
}
