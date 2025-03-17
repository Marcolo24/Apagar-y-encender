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

    // Rutas para categorías
    Route::get('/dashboard/admin/get-categorias', [AdminController::class, 'getCategorias'])
        ->name('dashboard.admin.get-categorias');
    
    Route::post('/dashboard/admin/crear-categoria', [AdminController::class, 'crearCategoria'])
        ->name('dashboard.admin.crear-categoria');
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
