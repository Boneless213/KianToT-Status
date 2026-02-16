<?php

use Illuminate\Support\Facades\Route;

// DEBUG: web.php load start
echo "DEBUG: routes/web.php loading...<br>";

Route::get('/', function () {
    echo "DEBUG: routes/web.php - hit root route, rendering welcome view...<br>";
    return view('welcome');
});

Route::get('/debug-simple', function () {
    return "DEBUG: Simple string response works! If you see this, the white screen is only on the home page.";
});
