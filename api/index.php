<?php

use Illuminate\Http\Request;

// Force error reporting at the very top
error_reporting(E_ALL);
ini_set('display_errors', '1');

define('LARAVEL_START', microtime(true));

// 1. Load the autoloader
require __DIR__ . '/../vendor/autoload.php';

// 2. Setup environment for Vercel
if (isset($_SERVER['VERCEL_URL'])) {
    putenv('LOG_CHANNEL=stderr');
    $storagePath = '/tmp/storage';
    foreach (['', '/framework', '/framework/views', '/framework/sessions', '/framework/cache', '/framework/cache/data', '/logs'] as $dir) {
        if (!is_dir($storagePath . $dir)) @mkdir($storagePath . $dir, 0755, true);
    }
    putenv("VIEW_COMPILED_PATH=$storagePath/framework/views");
}

// 3. Boot the App
$app = require_once __DIR__ . '/../bootstrap/app.php';

if (isset($_SERVER['VERCEL_URL'])) {
    $app->useStoragePath($storagePath);
}

// 4. Handle Request
$request = Request::capture();
$app->handleRequest($request);
