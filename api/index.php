<?php

// Vercel doesn't always set the document root correctly
$_SERVER['SCRIPT_NAME'] = '/index.php';

// Forward to the real index
require __DIR__ . '/../public/index.php';
