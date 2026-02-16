<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
try {
    /** @var Application $app */
    $app = require __DIR__ . '/../bootstrap/app.php';
    $app->handleRequest(Request::capture());
}
catch (\Throwable $e) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    echo "FATAL ERROR CAUGHT: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . " on line " . $e->getLine() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
