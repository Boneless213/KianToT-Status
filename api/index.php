<?php
// Ensure we see errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

// DEBUG
echo "DEBUG: api/index.php started<br>";

// FORCE CLEAR BOOTSTRAP CACHE ON VERCEL
$cacheDir = __DIR__ . '/../bootstrap/cache';
if (is_dir($cacheDir)) {
    echo "DEBUG: Checking $cacheDir...<br>";
    $files = glob($cacheDir . '/*.php');
    foreach ($files as $file) {
        if (basename($file) !== 'test.txt' && basename($file) !== '.gitignore') {
            echo "DEBUG: Deleting stale cache file: " . basename($file) . "<br>";
            @unlink($file);
        }
    }
}

require __DIR__ . '/../public/index.php';
