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
        // Verifica que el usuario autenticado sea un gestor
        if (Auth::user()->id_rol != 3) {
            return redirect()->route('index')->withErrors(['access' => 'No tienes permiso para acceder aquí.']);
        }
    
        // Obtener todas las prioridades y estados
        $prioridades = Prioridad::all();
        $estados = EstadoIncidencia::all();
    
        // Obtener el filtro de prioridad de la solicitud
        $filtroPrioridad = $request->input('filtro_prioridad');
        
        // Obtener el filtro de fecha de la solicitud
        $filtroFecha = $request->input('filtro_fecha', 'asc'); // Por defecto 'asc'
    
        // Consulta de incidencias con filtro por prioridad
        $query = Incidencia::with(['cliente', 'prioridad', 'estado', 'tecnico']);
    
        if (!empty($filtroPrioridad)) {
            $query->where('id_prioridad', $filtroPrioridad);
        }
    
        // Ordenar las incidencias por fecha
        $incidencias = $query->orderBy('fecha_inicio', $filtroFecha)->get();
    
        // Obtener todos los técnicos de la sede del gestor autenticado
        $tecnicos = User::where('id_rol', 2)->where('id_sede', Auth::user()->id_sede)->get();
    
        return view('dashboard.gestor', compact('incidencias', 'prioridades', 'estados', 'tecnicos'));
    }
                
    // Método para actualizar estado y prioridad de la incidencia
    public function updateIncidencia(Request $request, $id)
    {
        // Obtener la incidencia
        $incidencia = Incidencia::findOrFail($id);
    
        // Validar los datos recibidos (puedes agregar validaciones si es necesario)
        $data = $request->validate([
            'id_prioridad' => 'required|exists:prioridad,id',
            'id_estado' => 'required|exists:estado_incidencia,id',
        ]);
    
        // Verificar si el estado ha cambiado
        if ($incidencia->id_estado != $data['id_estado']) {
            // Si el estado cambia a "Sin asignar", eliminamos el técnico asignado
            $estadoSinAsignar = EstadoIncidencia::where('nombre', 'Sin asignar')->first();
            if ($data['id_estado'] == $estadoSinAsignar->id) {
                $incidencia->id_tecnico = null; // Eliminar el técnico
            }
        }
    
        // Actualizar los campos de la incidencia
        $incidencia->update($data);
    
        // Redirigir al gestor con un mensaje de éxito
        return redirect()->route('dashboard.gestor')->with('success', 'Incidencia actualizada correctamente');
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
        } else {
            // Si no se asigna ningún técnico, el estado se puede establecer en un valor predeterminado o vacío, si lo deseas
            $incidencia->id_estado = null;  // O cualquier estado que sea apropiado cuando no hay técnico
        }
    
        $incidencia->save();
    
        // Redirigir de nuevo al gestor con el mensaje de éxito
        return redirect()->route('dashboard.gestor')->with('success', 'Técnico asignado correctamente.');
    }
    }
