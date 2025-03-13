<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\Prioridad;
use App\Models\EstadoIncidencia; // Asegúrate de importar el modelo de EstadoIncidencia
use Illuminate\Http\Request;
use App\Models\User;

class GestorController extends Controller
{
    public function showIncidencias()
    {
        // Obtener todas las incidencias con sus relaciones
        $incidencias = Incidencia::with('cliente', 'prioridad', 'estado')->get(); // Asegúrate de incluir el estado

        // Obtener todas las prioridades y estados para mostrarlas en el formulario de actualización
        $prioridades = Prioridad::all();
        $estados = EstadoIncidencia::all(); // Asegúrate de cargar los estados

        // Retornar la vista con los datos
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

    // Método para ver las incidencias de cada técnico
    public function verIncidenciasTecnico()
    {
        // Obtener todas las incidencias asignadas a los técnicos
        $incidenciasAsignadas = Incidencia::whereNotNull('id_tecnico') // Solo las incidencias con técnico asignado
            ->with('cliente', 'prioridad', 'estado', 'tecnico') // Asegúrate de cargar las relaciones necesarias
            ->get();

        // Obtener todos los técnicos (usuarios con rol de técnico, asumiendo que id_rol 2 es el rol de técnico)
        $tecnicos = User::where('id_rol', 2)->get();

        // Retornar la vista con las incidencias y los técnicos
        return view('dashboard.incidencias-tecnico', compact('incidenciasAsignadas', 'tecnicos'));
    }
}
