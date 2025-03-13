<?php 

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GestorController;

// Página de inicio ahora muestra el login
Route::get('/', [AuthController::class, 'showLogin'])->name('index');

// Rutas de autenticación
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rutas protegidas por autenticación y control de roles
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard/admin', function (Request $request) {
        if (Auth::user()->id_rol != 1) {
            return redirect()->route('index'); // Redirigir si no es admin
        }
        return view('dashboard.admin');
    })->name('dashboard.admin');

    Route::get('/dashboard/tecnico', function (Request $request) {
        if (Auth::user()->id_rol != 2) {
            return redirect()->route('index'); // Redirigir si no es técnico
        }
        return view('dashboard.tecnico');
    })->name('dashboard.tecnico');

    Route::get('/dashboard/gestor', function (Request $request) {
        if (Auth::user()->id_rol != 3) {
            return redirect()->route('index'); // Redirigir si no es gestor
        }
        return view('dashboard.gestor');
    })->name('dashboard.gestor');

    Route::get('/dashboard/cliente', function (Request $request) {
        if (Auth::user()->id_rol != 4) {
            return redirect()->route('index'); // Redirigir si no es cliente
        }
        return view('dashboard.cliente');
    })->name('dashboard.cliente');
});
