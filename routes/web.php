<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/dashboard/admin', function () {
    return view('dashboard.admin');
});

