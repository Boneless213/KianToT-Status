<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "STEP 1: Starting...<br>";

if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    die("STEP 1.5: Vendor folder is missing!");
}

require __DIR__ . '/../vendor/autoload.php';
echo "STEP 2: Autoloaded.<br>";

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
    echo "STEP 3: Booting bootstrap/app.php...<br>";
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "STEP 4: App instance created.<br>";

    $app->useStoragePath($storagePath);
    echo "STEP 5: Storage path set.<br>";

    use Illuminate\Http\Request;
    $request = Request::capture();
    echo "STEP 6: Request captured.<br>";

    $response = $app->handleRequest($request);
    echo "STEP 7: Response generated.<br>";

    $response->send();
    echo "STEP 8: Finished!";
    
} catch (\Throwable $e) {
    echo "<h1>CRASH AT STEP " . __LINE__ . "</h1>";
    echo "<b>Error:</b> " . $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
