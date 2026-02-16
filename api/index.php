<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Force absolute paths and error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

require __DIR__ . '/../vendor/autoload.php';

// 2. Setup storage for Vercel
$storagePath = '/tmp/storage';
foreach (['', '/framework', '/framework/views', '/framework/sessions', '/framework/cache', '/framework/cache/data', '/logs'] as $dir) {
    if (!is_dir($storagePath . $dir)) @mkdir($storagePath . $dir, 0755, true);
}

// 3. Boot Laravel
try {
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->useStoragePath($storagePath);
    
    // Force Logging to stderr
    putenv('LOG_CHANNEL=stderr');

    $request = Request::capture();
    $response = $app->handleRequest($request);
    
    $response->send();
    $app->terminate($request, $response);
} catch (\Throwable $e) {
    echo "<h1>Laravel Initialization Error</h1>";
    echo "<p><b>Message:</b> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><b>Trace:</b> <pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
