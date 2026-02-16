<?php

use Illuminate\Http\Request;

// 1. Force Output Buffering and Error Reporting early
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

define('LARAVEL_START', microtime(true));

// 2. Load the autoloader - Absolute path check
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    die("CRITICAL: vendor/autoload.php is missing. Check Vercel build logs.");
}
require __DIR__ . '/../vendor/autoload.php';

// 3. Setup environment for Vercel
if (isset($_SERVER['VERCEL_URL'])) {
    $storagePath = '/tmp/storage';
    foreach (['', '/framework', '/framework/views', '/framework/sessions', '/framework/cache', '/framework/cache/data', '/logs'] as $dir) {
        if (!is_dir($storagePath . $dir)) @mkdir($storagePath . $dir, 0755, true);
    }
    
    putenv('LOG_CHANNEL=stderr');
    putenv("VIEW_COMPILED_PATH=$storagePath/framework/views");
    
    // Some Vercel environments need this to correctly handle the root route
    $_SERVER['SCRIPT_NAME'] = '/index.php';
}

try {
    // 4. Boot the App
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    if (isset($_SERVER['VERCEL_URL'])) {
        $app->useStoragePath($storagePath);
    }

    // 5. Handle Request
    $request = Request::capture();
    $response = $app->handleRequest($request);
    
    // 6. Send Response
    $response->send();
    $app->terminate($request, $response);
    
} catch (\Throwable $e) {
    ob_end_clean();
    echo "<h1>Laravel Entry Failure</h1>";
    echo "<b>Error:</b> " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "<b>File:</b> " . $e->getFile() . " on line " . $e->getLine() . "<br>";
    echo "<b>Trace:</b> <pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
