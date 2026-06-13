<?php

declare(strict_types=1);

namespace Core;

use Core\Exceptions\HttpException;
use Core\Middleware\AuthMiddleware;
use Core\Middleware\CsrfMiddleware;

final class Router
{
    private array $routes = [];

    public function get(string $path, array $handler, array $middleware = []): void
    {
        $this->add('GET', $path, $handler, $middleware);
    }

    public function post(string $path, array $handler, array $middleware = []): void
    {
        $this->add('POST', $path, $handler, $middleware);
    }

    private function add(string $method, string $path, array $handler, array $middleware): void
    {
        $this->routes[$method][$this->normalizePath($path)] = [
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(Request $request, Response $response): void
    {
        $method = $request->method();
        $path = $this->normalizePath($request->path());
        $route = $this->routes[$method][$path] ?? null;

        if ($route === null) {
            throw new HttpException('Route not found', 404);
        }

        foreach ($route['middleware'] as $middleware) {
            $this->runMiddleware($middleware, $request);
        }

        [$class, $method] = $route['handler'];
        $controller = new $class();
        $controller->{$method}($request, $response);
    }

    private function runMiddleware(string $middleware, Request $request): void
    {
        match ($middleware) {
            'auth' => (new AuthMiddleware())->handle($request),
            'csrf' => (new CsrfMiddleware())->handle($request),
            default => throw new HttpException('Middleware not registered', 500),
        };
    }

    private function normalizePath(string $path): string
    {
        $path = '/' . trim($path, '/');

        return $path === '/' ? '/' : rtrim($path, '/');
    }
}
