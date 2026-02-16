<?php
header('Content-Type: text/plain');

echo "CHECK_CACHE_V11\n";

$base = realpath(__DIR__ . '/..');
echo "Base: $base\n";

$targets = [
    'bootstrap/cache',
    'bootstrap',
    'vendor/laravel/framework/src/Illuminate/View'
];

foreach ($targets as $rel) {
    $path = $base . '/' . $rel;
    echo "\n--- $rel ---\n";
    if (is_dir($path)) {
        $items = scandir($path);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..')
                continue;
            echo(is_dir($path . '/' . $item) ? "[D] " : "[F] ") . $item . "\n";
        }
    }
    else {
        echo "MISSING OR NOT A DIR\n";
    }
}

echo "\n--- ENV CHECK ---\n";
echo "APP_ENV: " . getenv('APP_ENV') . "\n";
echo "APP_STORAGE: " . getenv('APP_STORAGE') . "\n";

echo "\nDONE\n";
