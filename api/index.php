<?php

use Illuminate\Http\Request;

// 1. Force the absolute most critical environment variables for Vercel
// These MUST be set before the autoloader runs in some cases
$tmpStorage = '/tmp/storage';
if (!is_dir($tmpStorage)) {
    @mkdir($tmpStorage, 0755, true);
    @mkdir($tmpStorage . '/framework/views', 0755, true);
    @mkdir($tmpStorage . '/framework/sessions', 0755, true);
    @mkdir($tmpStorage . '/framework/cache', 0755, true);
    @mkdir($tmpStorage . '/framework/cache/data', 0755, true);
    @mkdir($tmpStorage . '/logs', 0755, true);
}

// Redirect all possible writable paths to /tmp
putenv("APP_CONFIG_CACHE=$tmpStorage/framework/cache/config.php");
putenv("APP_ROUTES_CACHE=$tmpStorage/framework/cache/routes.php");
putenv("APP_SERVICES_CACHE=$tmpStorage/framework/cache/services.php");
putenv("APP_PACKAGES_CACHE=$tmpStorage/framework/cache/packages.php");
putenv("VIEW_COMPILED_PATH=$tmpStorage/framework/views");
putenv("LOG_CHANNEL=stderr");
putenv("SESSION_DRIVER=cookie");
putenv("CACHE_STORE=array");

// Force error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

define('LARAVEL_START', microtime(true));

// 2. Load Autoloader
require __DIR__ . '/../vendor/autoload.php';

try {
    // 3. Boot Laravel
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    
    // Explicitly set the storage path again
    $app->useStoragePath($tmpStorage);

    // 4. Handle Request
    $request = Request::capture();
    $response = $app->handleRequest($request);
    $response->send();
    $app->terminate($request, $response);

} catch (\Throwable $e) {
    // If it still crashes, we need to know why without relying on Laravel's view engine
    header('Content-Type: text/plain');
    echo "VERCEL BOOT ERROR\n";
    echo "=================\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n\n";
    echo "Trace:\n" . $e->getTraceAsString();
}
