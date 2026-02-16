<?php

use Illuminate\Http\Request;

// 1. Force environment variables at the very top (before anything else)
putenv('LOG_CHANNEL=stderr');
putenv('SESSION_DRIVER=cookie');
putenv('CACHE_STORE=array');
putenv('APP_CONFIG_CACHE=/tmp/storage/framework/cache/config.php');
putenv('APP_EVENTS_CACHE=/tmp/storage/framework/cache/events.php');
putenv('APP_PACKAGES_CACHE=/tmp/storage/framework/cache/packages.php');
putenv('APP_ROUTES_CACHE=/tmp/storage/framework/cache/routes.php');

// Force error reporting for diagnostics
error_reporting(E_ALL);
ini_set('display_errors', '1');

define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

try {
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    // 2. Force writable storage and cache paths
    if (isset($_SERVER['VERCEL_URL'])) {
        $storagePath = '/tmp/storage';
        $dirs = [
            '', 
            '/framework', 
            '/framework/views', 
            '/framework/sessions', 
            '/framework/cache', 
            '/framework/cache/data', 
            '/logs'
        ];
        
        foreach ($dirs as $dir) {
            if (!is_dir($storagePath . $dir)) {
                @mkdir($storagePath . $dir, 0755, true);
            }
        }
        
        $app->useStoragePath($storagePath);
        
        // Also ensure bootstrap cache points to /tmp if possible
        // (Laravel 11/12 uses the 'APP_..._CACHE' env vars we set above)
    }

    $app->handleRequest(Request::capture());
} catch (\Throwable $e) {
    echo "<h1>Recovering from Storage Error...</h1>";
    echo "<b>Diagnostic Error:</b> " . $e->getMessage() . "<br>";
    echo "<b>File:</b> " . $e->getFile() . " on line " . $e->getLine() . "<br>";
    echo "<b>Trace:</b> <pre>" . $e->getTraceAsString() . "</pre>";
}
