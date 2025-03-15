<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\Prioridad;
use App\Models\EstadoIncidencia; // Asegúrate de importar el modelo de EstadoIncidencia
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

        // Obtener incidencias relacionadas con cliente, prioridad y estado
        $incidencias = Incidencia::with(['cliente', 'prioridad', 'estado'])->get();
        $prioridades = Prioridad::all();
        $estados = EstadoIncidencia::all(); 

        // Retornar la vista con las variables necesarias
        return view('dashboard.gestor', compact('incidencias', 'prioridades', 'estados'));
    }

    // Método para actualizar la incidencia (estado y prioridad)
    public function updateIncidencia(Request $request, $id)
    {
        $incidencia = Incidencia::findOrFail($id);

        // Validación de los campos
        $request->validate([
            'id_prioridad' => 'required|exists:prioridad,id',  // Validar que la prioridad exista
            'id_estado' => 'required|exists:estado_incidencia,id',  // Validar que el estado exista
        ]);

        // Actualizar los valores de la incidencia
        $incidencia->id_prioridad = $request->input('id_prioridad');
        $incidencia->id_estado = $request->input('id_estado');
        $incidencia->save();

        // Redirigir con mensaje de éxito
        return redirect()->route('dashboard.gestor')->with('success', 'Incidencia actualizada con éxito.');
    }

    // Método para ver las incidencias y asignar técnicos
    public function verIncidenciasTecnico()
    {
        // Obtener todas las incidencias, incluyendo las que no tienen técnico asignado
        $incidenciasAsignadas = Incidencia::with('cliente', 'prioridad', 'estado', 'tecnico')->get();

        // Obtener todos los técnicos (usuarios con id_rol = 2)
        $tecnicos = User::where('id_rol', 2)->get();

        return view('dashboard.incidencias-tecnico', compact('incidenciasAsignadas', 'tecnicos'));
    }

    public function updateTecnico(Request $request, $id)
    {
        // Validar que el técnico seleccionado existe y tiene el rol adecuado
        $request->validate([
            'id_tecnico' => 'nullable|exists:users,id'
        ]);

        // Buscar la incidencia
        $incidencia = Incidencia::findOrFail($id);

        // Asignar el técnico
        $incidencia->id_tecnico = $request->id_tecnico;
        $incidencia->save();

        return redirect()->route('gestor.verIncidenciasTecnico')->with('success', 'Técnico asignado correctamente.');
    }

}
