<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Ensure we see errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Clear buffers
while (ob_get_level())
    ob_end_clean();

define('LARAVEL_START', microtime(true));

echo "--- START OF DIAGNOSTICS ---<br>";
echo "PHP Version: " . PHP_VERSION . "<br>";

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    echo "Maintenance mode file found<br>";
    require $maintenance;
}

// Register the Composer autoloader...
echo "Loading autoloader...<br>";
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
    echo "Autoloader loaded<br>";
}
else {
    echo "FATAL: Autoloader NOT FOUND at " . realpath(__DIR__ . '/../vendor/autoload.php') . "<br>";
    exit;
}

// Bootstrap Laravel and handle the request...
try {
    echo "Requiring bootstrap/app.php...<br>";
    /** @var Application $app */
    $app = require __DIR__ . '/../bootstrap/app.php';
    echo "app.php returned: " . (is_object($app) ? get_class($app) : gettype($app)) . "<br>";

    echo "Capturing request and handling...<br>";
    $app->handleRequest(Request::capture());
    echo "Request handled successfully<br>";
}
catch (\Throwable $e) {
    echo "FATAL ERROR CAUGHT: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . " on line " . $e->getLine() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "--- END OF DIAGNOSTICS ---<br>";
