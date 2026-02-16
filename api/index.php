<?php

use Illuminate\Http\Request;

// Force error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

try {
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    // --- Vercel Serverless Optimization ---
    if (isset($_SERVER['VERCEL_URL'])) {
        // 1. Force writable storage in /tmp
        $path = '/tmp/storage';
        foreach (['', '/framework/views', '/framework/sessions', '/framework/cache', '/framework/cache/data', '/logs'] as $dir) {
            if (!is_dir($path . $dir)) @mkdir($path . $dir, 0755, true);
        }
        $app->useStoragePath($path);

        // 2. Force Logging to stderr (not a file)
        putenv('LOG_CHANNEL=stderr');
        
        // 3. Force Cache/Session to non-file drivers if possible, or /tmp
        putenv('SESSION_DRIVER=cookie');
        putenv('CACHE_STORE=array'); 
    }

    $app->handleRequest(Request::capture());
} catch (\Throwable $e) {
    echo "<h1>Almost there...</h1>";
    echo "<b>Diagnostic Error:</b> " . $e->getMessage() . "<br>";
    echo "<b>File:</b> " . $e->getFile() . " on line " . $e->getLine() . "<br>";
    echo "<b>Trace:</b> <pre>" . $e->getTraceAsString() . "</pre>";
}
