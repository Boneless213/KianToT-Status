<?php
header('Content-Type: text/plain');
echo "Current directory: " . __DIR__ . "\n";
echo "Root directory contents:\n";
print_r(scandir(__DIR__ . '/..'));

$publicDir = __DIR__ . '/../public';
if (is_dir($publicDir)) {
    echo "\nPublic directory contents:\n";
    print_r(scandir($publicDir));

    $buildDir = $publicDir . '/build';
    if (is_dir($buildDir)) {
        echo "\nBuild directory contents:\n";
        print_r(scandir($buildDir));

        $manifestPath = $buildDir . '/manifest.json';
        if (file_exists($manifestPath)) {
            echo "\nManifest content:\n";
            echo file_get_contents($manifestPath) . "\n";
        }

        $assetsDir = $buildDir . '/assets';
        if (is_dir($assetsDir)) {
            echo "\nAssets directory contents:\n";
            print_r(scandir($assetsDir));
        }
    }
}
else {
    echo "\nPUBLIC DIRECTORY NOT FOUND at $publicDir\n";
}
