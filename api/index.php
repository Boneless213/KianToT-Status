<?php
// DEBUG: Entry point check
echo "DEBUG: api/index.php started<br>";

ini_set('display_errors', 1);
error_reporting(E_ALL);
require __DIR__ . '/../public/index.php';
