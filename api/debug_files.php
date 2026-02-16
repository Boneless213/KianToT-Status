<?php
header('Content-Type: text/plain');

echo "REMOTE DEBUG START\n";
echo "PHP: " . PHP_VERSION . "\n";
echo "Dir: " . __DIR__ . "\n";

function scan($path, $level = 0)
{
    if ($level > 3)
        return;
    if (!is_dir($path))
        return;

    $items = scandir($path);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..')
            continue;
        $full = $path . '/' . $item;
        echo str_repeat("  ", $level) . (is_dir($full) ? "[D] " : "[F] ") . $item . "\n";
        if (is_dir($full))
            scan($full, $level + 1);
    }
}

echo "\n--- Structure ---\n";
scan(realpath(__DIR__ . '/..'));

echo "\nREMOTE DEBUG END\n";
