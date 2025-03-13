<?php 

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GestorController;
use App\Http\Controllers\IncidenciaController;

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

    Route::get('/dashboard/tecnico', [IncidenciaController::class, 'index'])->name('dashboard.tecnico');

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

    // Rutas para las acciones de las incidencias
    Route::get('/incidencia/asignar/{id}', [IncidenciaController::class, 'asignar'])->name('incidencia.asignar');
    Route::get('/incidencia/desasignar/{id}', [IncidenciaController::class, 'desasignar'])->name('incidencia.desasignar');
    Route::get('/incidencias/empezar/{id}', [IncidenciaController::class, 'empezar'])->name('incidencias.empezar');
    Route::get('/incidencias/resolver/{id}', [IncidenciaController::class, 'resolver'])->name('incidencias.resolver');
    Route::get('/incidencias/mensaje/{id}', [IncidenciaController::class, 'mensaje'])->name('incidencias.mensaje');
});
