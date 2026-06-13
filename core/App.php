<?php

declare(strict_types=1);

namespace Core;

use App\Controllers\AuthController;
use App\Controllers\CsrfController;
use App\Controllers\HomeController;
use App\Controllers\UserController;
use Core\Exceptions\HttpException;

final class App
{
    public function run(): void
    {
        $request = Request::capture();
        $response = new Response();
        $router = new Router();

        $this->registerRoutes($router);

        try {
            $router->dispatch($request, $response);
        } catch (HttpException $exception) {
            $response->json([
                'message' => $exception->getMessage(),
            ], $exception->getStatusCode());
        } catch (\Throwable $exception) {
            $status = 500;
            $payload = ['message' => 'Internal Server Error'];

            if ((bool) config('app.debug', false)) {
                $payload['error'] = $exception->getMessage();
            }

            $response->json($payload, $status);
        }
    }

    private function registerRoutes(Router $router): void
    {
        $router->get('/', [HomeController::class, 'index']);
        $router->get('/api/csrf-token', [CsrfController::class, 'show']);

        $router->post('/api/auth/register', [AuthController::class, 'register'], ['csrf']);
        $router->post('/api/auth/login', [AuthController::class, 'login'], ['csrf']);
        $router->get('/api/me', [UserController::class, 'me'], ['auth']);
    }
}
