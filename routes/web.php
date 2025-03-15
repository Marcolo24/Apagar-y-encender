<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GestorController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\AdminController;


// Página de inicio con el formulario de login
Route::get('/', [AuthController::class, 'showLogin'])->name('index');

// Rutas de autenticación
Route::post('/', [AuthController::class, 'login'])->name('login');
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


    Route::get('/dashboard/tecnico', [IncidenciaController::class, 'index'])->name('dashboard.tecnico');

    /*Route::get('/dashboard/gestor', function (Request $request) {
        if (Auth::user()->id_rol != 3) {
            return redirect()->route('index'); // Redirigir si no es gestor
        }
        return view('dashboard.gestor');
    })->name('dashboard.gestor');*/

    Route::get('/dashboard/gestor', [GestorController::class, 'showIncidencias'])->name('dashboard.gestor');

    /*Route::get('/dashboard/cliente', function (Request $request) {
        if (Auth::user()->id_rol != 4) {
            return redirect()->route('index'); // Redirigir si no es cliente
        }
        return view('dashboard.cliente');
    })->name('dashboard.cliente');*/

    // Rutas para las acciones de las incidencias
    Route::get('/incidencia/asignar/{id}', [IncidenciaController::class, 'asignar'])->name('incidencia.asignar');
    Route::get('/incidencia/desasignar/{id}', [IncidenciaController::class, 'desasignar'])->name('incidencia.desasignar');
    Route::get('/incidencias/empezar/{id}', [IncidenciaController::class, 'empezar'])->name('incidencias.empezar');
    Route::get('/incidencias/resolver/{id}', [IncidenciaController::class, 'resolver'])->name('incidencias.resolver');
    Route::get('/incidencias/mensaje/{id}', [IncidenciaController::class, 'mensaje'])->name('incidencias.mensaje');

    Route::get('/dashboard/cliente', [ClienteController::class, 'index'])->name('dashboard.cliente');
    Route::put('/incidencias/cerrar/{id}', [ClienteController::class, 'cerrarIncidencia'])->name('incidencias.cerrar');
    Route::post('/incidencias/crear', [ClienteController::class, 'crearIncidencia'])->name('incidencias.crear');
// Ruta para actualizar la incidencia (estado y prioridad)
Route::put('/gestor/incidencia/{id}/update-incidencia', [GestorController::class, 'updateIncidencia'])->name('gestor.updateIncidencia');
});

// Ruta para ver incidencias asignadas a técnicos
Route::get('/dashboard/gestor/incidencias-tecnico', [GestorController::class, 'verIncidenciasTecnico'])->name('gestor.verIncidenciasTecnico');
