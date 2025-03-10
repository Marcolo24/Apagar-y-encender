<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

// Página de inicio ahora muestra el login
Route::get('/', [AuthController::class, 'showLogin'])->name('index');
Route::get('/dashboard/admin', function () {
    return view('dashboard.admin');
})->name('dashboard.admin');


// Rutas de autenticación
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.admin');
    })->name('home');
});