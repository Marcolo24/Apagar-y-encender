<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\Prioridad;
use App\Models\EstadoIncidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GestorController extends Controller
{
    public function showIncidencias()
    {
        // Verifica que el usuario autenticado sea un gestor
        if (Auth::user()->id_rol != 3) {
            return redirect()->route('index')->withErrors(['access' => 'No tienes permiso para acceder aquí.']);
        }

        // Obtener incidencias con relaciones de cliente, prioridad y estado
        $incidencias = Incidencia::with(['cliente', 'prioridad', 'estado'])->get();
        $prioridades = Prioridad::all();
        $estados = EstadoIncidencia::all(); 

        return view('dashboard.gestor', compact('incidencias', 'prioridades', 'estados'));
    }

    // Método para actualizar estado y prioridad de la incidencia
    public function updateIncidencia(Request $request, $id)
    {
        $incidencia = Incidencia::findOrFail($id);

        // Validación de los campos
        $request->validate([
            'id_prioridad' => 'required|exists:prioridad,id',
            'id_estado' => 'required|exists:estado_incidencia,id',
        ]);

        // Actualizar prioridad y estado
        $incidencia->id_prioridad = $request->input('id_prioridad');
        $incidencia->id_estado = $request->input('id_estado');
        $incidencia->save();

        return redirect()->route('dashboard.gestor')->with('success', 'Incidencia actualizada con éxito.');
    }

    public function verIncidenciasTecnico()
    {
        // Obtener la sede del gestor autenticado
        $sedeGestor = Auth::user()->id_sede;
    
        // Obtener todas las incidencias con relaciones
        $incidenciasAsignadas = Incidencia::with('cliente', 'prioridad', 'estado', 'tecnico')->get();
    
        // Obtener técnicos de la misma sede
        $tecnicos = User::where('id_rol', 2)->where('id_sede', $sedeGestor)->get();
    
        return view('dashboard.incidencias-tecnico', compact('incidenciasAsignadas', 'tecnicos'));
    }
    
    public function updateTecnico(Request $request, $id)
    {
        // Validar que el técnico seleccionado existe o sea null
        $request->validate([
            'id_tecnico' => 'nullable|exists:users,id'
        ]);

        // Buscar la incidencia
        $incidencia = Incidencia::findOrFail($id);

        // Asignar el técnico
        $incidencia->id_tecnico = $request->id_tecnico;

        // Si se asigna un técnico, cambiar el estado a "Asignada"
        if ($request->id_tecnico) {
            $estadoAsignada = EstadoIncidencia::where('nombre', 'Asignada')->first();
            if ($estadoAsignada) {
                $incidencia->id_estado = $estadoAsignada->id;
            }
        }

        $incidencia->save();

        return redirect()->route('gestor.verIncidenciasTecnico')->with('success', 'Técnico asignado correctamente.');
    }
}
