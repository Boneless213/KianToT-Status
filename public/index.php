<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// DEBUG: public/index.php started
echo "DEBUG: public/index.php started<br>";

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
echo "DEBUG: public/index.php - loading autoloader...<br>";
require __DIR__ . '/../vendor/autoload.php';
echo "DEBUG: public/index.php - autoloader loaded<br>";

// Bootstrap Laravel and handle the request...
echo "DEBUG: public/index.php - requiring app.php...<br>";
/** @var Application $app */
$app = require __DIR__ . '/../bootstrap/app.php';

echo "DEBUG: public/index.php - app.php loaded, handling request (with try-catch)...<br>";

try {
    $app->handleRequest(Request::capture());
    echo "DEBUG: public/index.php - handleRequest finished normally<br>";
}
catch (\Throwable $e) {
    echo "DEBUG: FATAL ERROR CAUGHT: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . " on line " . $e->getLine() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "DEBUG: public/index.php - end of file reached<br>";
