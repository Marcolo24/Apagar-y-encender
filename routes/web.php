<?php 

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GestorController;
use App\Http\Controllers\AdminController;

// Página de inicio con el formulario de login
Route::get('/', [AuthController::class, 'showLogin'])->name('index');

// Rutas de autenticación
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Ruta del Gestor que muestra las incidencias
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/gestor', [GestorController::class, 'showIncidencias'])
        ->name('dashboard.gestor');

    Route::get('/dashboard/admin', [AdminController::class, 'showUsers'])
        ->name('dashboard.admin');
    
});

// Rutas protegidas por autenticación y roles
Route::middleware(['auth'])->group(function () {


    Route::get('/dashboard/tecnico', function (Request $request) {
        if (Auth::user()->id_rol != 2) {
            return redirect()->route('index'); // Redirigir si no es técnico
        }
        return view('dashboard.tecnico');
    })->name('dashboard.tecnico');

    Route::get('/dashboard/cliente', function (Request $request) {
        if (Auth::user()->id_rol != 4) {
            return redirect()->route('index'); // Redirigir si no es cliente
        }
        return view('dashboard.cliente');
    })->name('dashboard.cliente');
});
