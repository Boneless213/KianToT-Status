<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Diagnostics and error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// 2. Load Autoloader
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    echo "<h1>CRITICAL ERROR</h1>";
    echo "Vendor folder is missing. This means Composer did not run. <br>";
    echo "Current directory: " . __DIR__ . "<br>";
    echo "Trying to list parent directory: <pre>";
    print_r(scandir(__DIR__ . '/..'));
    echo "</pre>";
    die();
}
require __DIR__ . '/../vendor/autoload.php';

// 3. Prepare writable storage
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
    // 4. Manual path overrides BEFORE boot
    putenv("APP_CONFIG_CACHE=$storagePath/framework/cache/config.php");
    putenv("APP_ROUTES_CACHE=$storagePath/framework/cache/routes.php");
    putenv("VIEW_COMPILED_PATH=$storagePath/framework/views");

    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->useStoragePath($storagePath);

    $request = Request::capture();
    $response = $app->handleRequest($request);
    $response->send();
    $app->terminate($request, $response);
} catch (\Throwable $e) {
    http_response_code(500);
    echo "<h1>Laravel Initialization Error</h1>";
    echo "<p><b>Message:</b> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><b>File:</b> " . $e->getFile() . " on line " . $e->getLine() . "</p>";
    echo "<h3>Stack Trace:</h3><pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
