<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();

if (isset($_SERVER['VERCEL_URL']) || env('APP_ENV') === 'production') {
    $path = '/tmp/storage';
    if (!is_dir($path)) {
        @mkdir($path, 0755, true);
        @mkdir($path . '/framework/views', 0755, true);
        @mkdir($path . '/framework/sessions', 0755, true);
        @mkdir($path . '/framework/cache', 0755, true);
        @mkdir($path . '/framework/cache/data', 0755, true);
    }
    $app->useStoragePath($path);
}

return $app;
