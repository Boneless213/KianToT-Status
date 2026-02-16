<?php

// THE LUNAR TEST - STEP BY STEP
echo "1. PHP ALIVE<br>";

// Force errors to show immediately
error_reporting(E_ALL);
ini_set('display_errors', '1');

$autoload = __DIR__ . '/../vendor/autoload.php';
echo "2. CHECKING AUTOLOAD: " . (file_exists($autoload) ? "FOUND" : "MISSING") . "<br>";

if (file_exists($autoload)) {
    require $autoload;
    echo "3. AUTOLOAD LOADED<br>";
} else {
    echo "3. CANNOT PROCEED - VENDOR MISSING<br>";
    echo "DIRECTORIES IN ROOT: <pre>";
    print_r(scandir(__DIR__ . '/..'));
    echo "</pre>";
    die();
}

try {
    echo "4. BOOTING APP...<br>";
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "5. APP INSTANCE CREATED<br>";

    $storagePath = '/tmp/storage';
    foreach (['', '/framework', '/framework/views', '/framework/sessions', '/framework/cache', '/framework/cache/data', '/logs'] as $dir) {
        if (!is_dir($storagePath . $dir)) @mkdir($storagePath . $dir, 0755, true);
    }
    $app->useStoragePath($storagePath);
    echo "6. STORAGE READY<br>";

    $request = Illuminate\Http\Request::capture();
    $response = $app->handleRequest($request);
    echo "7. REQUEST HANDLED<br>";

    $response->send();
    echo "8. DONE";
} catch (\Throwable $e) {
    echo "<h1>CAUGHT ERROR:</h1>" . $e->getMessage();
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
