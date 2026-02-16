<?php
echo "<!-- PHP RUNTIME ACTIVE -->";

// Force error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Vercel doesn't always set the document root correctly
$_SERVER['SCRIPT_NAME'] = '/index.php';

// Forward to the real index
require __DIR__ . '/../public/index.php';
