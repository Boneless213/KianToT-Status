<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

$storagePath = '/tmp/storage';
if (isset($_SERVER['VERCEL_URL'])) {
    foreach (['', '/framework', '/framework/views', '/framework/sessions', '/framework/cache', '/framework/cache/data', '/logs'] as $dir) {
        if (!is_dir($storagePath . $dir)) @mkdir($storagePath . $dir, 0755, true);
    }
}

try {
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    if (isset($_SERVER['VERCEL_URL'])) {
        $app->useStoragePath($storagePath);
    }

    $request = Request::capture();
    $response = $app->handleRequest($request);
    $response->send();
    $app->terminate($request, $response);
} catch (\Throwable $e) {
    echo "<h1>Boot Error</h1>";
    echo "<b>Message:</b> " . $e->getMessage();
}
