<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $a = 1;
    return ['Laravel' => app()->version()];
});

Route::get('/phpinfo', function () {
    return phpinfo();
});


require __DIR__.'/auth.php';
