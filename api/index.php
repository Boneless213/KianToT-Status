<?php

// Diagnostics Mode
error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "Checking environment...<br>";

$autoload = __DIR__ . '/../vendor/autoload.php';
$appPath = __DIR__ . '/../bootstrap/app.php';

if (!file_exists($autoload)) {
    die("Error: vendor/autoload.php not found! Did composer install run?");
}
echo "✓ Autoloader found.<br>";

if (!file_exists($appPath)) {
    die("Error: bootstrap/app.php not found!");
}
echo "✓ App bootstrap found.<br>";

try {
    echo "Booting Laravel...<br>";
    require $autoload;
    $app = require_once $appPath;
    
    echo "Laravel Booted! Handling request...<br>";
    $app->handleRequest(Illuminate\Http\Request::capture());
} catch (\Throwable $e) {
    echo "<h3>Laravel Crash!</h3>";
    echo "<b>Message:</b> " . $e->getMessage() . "<br>";
    echo "<b>File:</b> " . $e->getFile() . " on line " . $e->getLine() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
