<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__."/../routes/web.php",
        commands: __DIR__."/../routes/console.php",
        health: "/up",
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->useStoragePath(
        isset($_SERVER["VERCEL_URL"]) || env("APP_ENV") === "production" 
            ? (function() {
                $path = "/tmp/storage";
                if (!is_dir($path)) {
                    @mkdir($path, 0755, true);
                    @mkdir($path . "/framework/views", 0755, true);
                    @mkdir($path . "/framework/sessions", 0755, true);
                    @mkdir($path . "/framework/cache", 0755, true);
                    @mkdir($path . "/framework/cache/data", 0755, true);
                }
                return $path;
            })()
            : storage_path()
    )
    ->create();
