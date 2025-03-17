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
    public function showIncidencias(Request $request)
    {
        if (Auth::user()->id_rol != 3) {
            return redirect()->route('index')->withErrors(['access' => 'No tienes permiso para acceder aquí.']);
        }
    
        // Obtener filtros
        $filtroPrioridad = $request->input('filtro_prioridad');
        $filtroTecnico = $request->input('filtro_tecnico');
        $filtroFecha = $request->input('filtro_fecha', 'asc');
    
        // Obtener prioridades, estados y técnicos
        $prioridades = Prioridad::all();
        $estados = EstadoIncidencia::all();
        $tecnicos = User::where('id_rol', 2)->where('id_sede', Auth::user()->id_sede)->get();
    
        // Obtener estado "Cerrada"
        $estadoCerrada = EstadoIncidencia::where('nombre', 'Cerrada')->first();
    
        // Consulta de incidencias
        $query = Incidencia::with(['cliente', 'prioridad', 'estado', 'tecnico'])
            ->when($estadoCerrada, function ($query) use ($estadoCerrada) {
                return $query->where('id_estado', '!=', $estadoCerrada->id);
            });
    
        // Aplicar filtro por prioridad
        if (!empty($filtroPrioridad)) {
            $query->where('id_prioridad', $filtroPrioridad);
        }
    
        // Aplicar filtro por técnico
        if (!empty($filtroTecnico)) {
            $query->where('id_tecnico', $filtroTecnico);
        }
    
        // Ordenar por fecha
        $incidencias = $query->orderBy('fecha_inicio', $filtroFecha)->get();
    
        return view('dashboard.gestor', compact('incidencias', 'prioridades', 'estados', 'tecnicos'));
    }
                        
    // Método para actualizar estado y prioridad de la incidencia
    public function updateIncidencia(Request $request, $id)
    {
        // Obtener la incidencia
        $incidencia = Incidencia::findOrFail($id);
    
        // Validar los datos recibidos
        $data = $request->validate([
            'id_prioridad' => 'required|exists:prioridad,id',
            'id_estado' => 'required|exists:estado_incidencia,id',
        ]);
    
        // Verificar si el estado ha cambiado a "Sin asignar"
        $estadoSinAsignar = EstadoIncidencia::where('nombre', 'Sin asignar')->first();
        if ($estadoSinAsignar && $data['id_estado'] == $estadoSinAsignar->id) {
            $incidencia->id_tecnico = null; // Eliminar el técnico asignado
        }
    
        // Actualizar los datos
        $incidencia->update($data);
        
        return redirect()->route('dashboard.gestor')->with('success', 'Incidencia actualizada correctamente.');
    }
            
    public function verIncidenciasTecnico()
    {
        // Obtener la sede del gestor autenticado
        $sedeGestor = Auth::user()->id_sede;
    
        // Obtener todas las incidencias con relaciones
        $incidenciasAsignadas = Incidencia::with('cliente', 'prioridad', 'estado', 'tecnico')->get();
    
        // Obtener técnicos de la misma sede
        $tecnicos = User::where('id_rol', 2)->where('id_sede', $sedeGestor)->get();
    
        return view('dashboard.gestor', compact('incidenciasAsignadas', 'tecnicos'));
    }
    
    public function updateTecnico(Request $request, $id)
    {
        // Validar el técnico (puede ser null)
        $request->validate([
            'id_tecnico' => 'nullable|exists:users,id'
        ]);
    
        // Obtener la incidencia
        $incidencia = Incidencia::findOrFail($id);
    
        // Asignar el técnico o eliminarlo si es null
        $incidencia->id_tecnico = $request->id_tecnico;
    
        // Obtener estados
        $estadoAsignada = EstadoIncidencia::where('nombre', 'Asignada')->first();
        $estadoSinAsignar = EstadoIncidencia::where('nombre', 'Sin asignar')->first();
    
        // Si hay un técnico, cambia el estado a "Asignada"
        if ($request->id_tecnico && $estadoAsignada) {
            $incidencia->id_estado = $estadoAsignada->id;
        }
        // Si no hay técnico, cambia el estado a "Sin asignar"
        elseif (!$request->id_tecnico && $estadoSinAsignar) {
            $incidencia->id_estado = $estadoSinAsignar->id;
        }
    
        $incidencia->save();
    
        return redirect()->route('dashboard.gestor')->with('success', 'Técnico actualizado correctamente.');
    }

}
