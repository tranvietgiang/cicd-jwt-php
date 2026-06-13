<?php

declare(strict_types=1);

namespace Core;

final class Request
{
    private array $user = [];

    public static function capture(): self
    {
        return new self();
    }

    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public function path(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $scriptDir = $this->basePath();

        if ($scriptDir !== '' && $scriptDir !== '/' && str_starts_with($uri, $scriptDir)) {
            $uri = substr($uri, strlen($scriptDir)) ?: '/';
        }

        return $uri;
    }

    private function basePath(): string
    {
        $scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');

        if (str_ends_with($scriptDir, '/public')) {
            $scriptDir = substr($scriptDir, 0, -7);
        }

        return $scriptDir;
    }

    public function input(): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (str_contains($contentType, 'application/json')) {
            $json = json_decode(file_get_contents('php://input'), true);

            return is_array($json) ? $json : [];
        }

        return $_POST;
    }

    public function header(string $name): ?string
    {
        $key = 'HTTP_' . strtoupper(str_replace('-', '_', $name));

        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        }

        if ($key === 'HTTP_AUTHORIZATION') {
            return $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? null;
        }

        return null;
    }

    public function bearerToken(): ?string
    {
        $header = $this->header('Authorization') ?? '';

        if (preg_match('/^Bearer\s+(.+)$/i', $header, $matches) !== 1) {
            return null;
        }

        return trim($matches[1]);
    }

    public function setUser(array $user): void
    {
        $this->user = $user;
    }

    public function user(): array
    {
        return $this->user;
    }
}
