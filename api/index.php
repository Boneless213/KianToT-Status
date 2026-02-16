<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Silent autoloader
require __DIR__ . '/../vendor/autoload.php';

// 2. Prepare writable storage
$storagePath = '/tmp/storage';
if (!is_dir($storagePath)) {
    @mkdir($storagePath, 0755, true);
    @mkdir($storagePath . '/framework/views', 0755, true);
    @mkdir($storagePath . '/framework/sessions', 0755, true);
    @mkdir($storagePath . '/framework/cache', 0755, true);
    @mkdir($storagePath . '/framework/cache/data', 0755, true);
    @mkdir($storagePath . '/logs', 0755, true);
}

try {
    // 3. Boot Laravel
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->useStoragePath($storagePath);

    // 4. Handle Request
    // In Laravel 11/12, handleRequest() sends the response and terminates the app itself.
    $app->handleRequest(Request::capture());
} catch (\Throwable $e) {
    if (env('APP_DEBUG')) {
        echo "<h1>Initialization Error</h1>";
        echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        http_response_code(500);
        die("Internal Server Error");
    }
}
