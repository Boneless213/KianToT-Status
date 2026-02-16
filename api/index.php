<?php

// Show ALL errors for debugging the white screen
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Forward the request to the public/index.php
require __DIR__ . '/../public/index.php';
