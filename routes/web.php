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
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;


// Página de inicio con el formulario de login
Route::get('/', [AuthController::class, 'showLogin'])->name('index');

// Rutas de autenticación
Route::post('/', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Ruta del Gestor que muestra las incidencias
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/gestor', [GestorController::class, 'showIncidencias'])
        ->name('dashboard.gestor');

    // Ruta del Admin que muestra los usuarios
    Route::get('/dashboard/admin', [AdminController::class, 'showUsers'])
        ->name('dashboard.admin');

    // Buscar usuarios con fetch 
    Route::get('/dashboard/admin/buscar-usuarios', [AdminController::class, 'buscarUsuarios'])
        ->name('dashboard.admin.buscar-usuarios');  

    // Nueva ruta para eliminar usuarios
    Route::delete('/dashboard/admin/eliminar-usuario/{id}', [AdminController::class, 'eliminarUsuario'])
        ->name('dashboard.admin.eliminar-usuario');

    // Ruta para obtener sedes y roles
    Route::get('/dashboard/admin/get-sedes-roles', [AdminController::class, 'getSedesRoles'])
        ->name('dashboard.admin.get-sedes-roles');

    // Nueva ruta para editar usuarios
    Route::put('/dashboard/admin/editar-usuario/{id}', [AdminController::class, 'editarUsuario'])
        ->name('dashboard.admin.editar-usuario');

    // Nueva ruta para crear usuarios
    Route::post('/dashboard/admin/crear-usuario', [AdminController::class, 'crearUsuario'])
        ->name('dashboard.admin.crear-usuario');
});

// Rutas protegidas por autenticación y roles
Route::middleware(['auth'])->group(function () {


    Route::get('/dashboard/tecnico', [IncidenciaController::class, 'index'])->name('dashboard.tecnico');

    /*Route::get('/dashboard/gestor', function (Request $request) {
        if (Auth::user()->id_rol != 3) {
            return Redirect::route('index');
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

// Ruta para actualizar el técnico
Route::put('/incidencias/{id}/update-tecnico', [GestorController::class, 'updateTecnico'])->name('incidencias.updateTecnico');

Route::post('/incidencias/{id}/asignar-tecnico', [GestorController::class, 'updateTecnico'])->name('incidencias.asignar.tecnico');

Route::get('/dashboard/tecnico/buscar-incidencias', [IncidenciaController::class, 'buscarIncidencias']);