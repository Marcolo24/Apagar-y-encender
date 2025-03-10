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
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/admin', function () {
        return view('dashboard.admin');
    })->middleware('role:1')->name('dashboard.admin');

    Route::get('/dashboard/tecnico', function () {
        return view('dashboard.tecnico');
    })->middleware('role:2')->name('dashboard.tecnico');

    Route::get('/dashboard/gestor', function () {
        return view('dashboard.gestor');
    })->middleware('role:3')->name('dashboard.gestor');

    Route::get('/dashboard/cliente', function () {
        return view('dashboard.cliente');
    })->middleware('role:4')->name('dashboard.cliente');
});