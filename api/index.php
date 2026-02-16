<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Load the autoloader
require __DIR__ . '/../vendor/autoload.php';

// 2. Setup environment for Vercel
if (isset($_SERVER['VERCEL_URL'])) {
    // Force logs to stderr
    putenv('LOG_CHANNEL=stderr');

    // Create and use writable static storage
    $storagePath = '/tmp/storage';
    foreach (['', '/framework', '/framework/views', '/framework/sessions', '/framework/cache', '/framework/cache/data', '/logs'] as $dir) {
        if (!is_dir($storagePath . $dir)) @mkdir($storagePath . $dir, 0755, true);
    }
    
    // We set this here so Laravel knows where to write compiled views
    putenv("VIEW_COMPILED_PATH=$storagePath/framework/views");
}

// 3. Boot the App
$app = require_once __DIR__ . '/../bootstrap/app.php';

if (isset($_SERVER['VERCEL_URL'])) {
    $app->useStoragePath($storagePath);
}

// 4. Handle Request
$app->handleRequest(Request::capture());
