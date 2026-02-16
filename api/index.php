<?php

use Illuminate\Http\Request;

// --- Production Error Reporting ---
error_reporting(E_ALL);
ini_set('display_errors', '1');

define('LARAVEL_START', microtime(true));

// Ensure vendor exists
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    die("Vendor folder is missing. Check Vercel build logs for Composer errors.");
}

require __DIR__ . '/../vendor/autoload.php';

try {
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    // Force writable storage on Vercel
    if (isset($_SERVER['VERCEL_URL'])) {
        $path = '/tmp/storage';
        foreach (['', '/framework/views', '/framework/sessions', '/framework/cache', '/framework/cache/data'] as $dir) {
            if (!is_dir($path . $dir)) @mkdir($path . $dir, 0755, true);
        }
        $app->useStoragePath($path);
    }

    $app->handleRequest(Request::capture());
} catch (\Throwable $e) {
    echo "<h1>Site is loading...</h1>";
    echo "<b>Diagnostic Error:</b> " . $e->getMessage() . "<br>";
    echo "<b>Trace:</b> <pre>" . $e->getTraceAsString() . "</pre>";
}
