<?php
namespace App\Http\Controllers;

use App\Models\Incidencia;
use Illuminate\Http\Request;

class GestorController extends Controller
{
    public function showIncidencias()
    {
        // Obtener todas las incidencias con sus relaciones
        $incidencias = Incidencia::with('cliente', 'prioridad')->get();

        // Retornar la vista con los datos
        return view('dashboard.gestor', compact('incidencias'));
    }
}
