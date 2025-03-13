<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Incidencia;
use App\Models\EstadoIncidencia;
use Carbon\Carbon;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        // Verifica que el usuario autenticado sea un cliente
        if (Auth::user()->id_rol != 4) {
            return redirect()->route('index')->withErrors(['access' => 'No tienes permiso para acceder aquí.']);
        }

        // Obtener incidencias del cliente autenticado con filtros opcionales
        $query = Incidencia::where('id_cliente', Auth::id())
                            ->with(['estado', 'prioridad', 'subcategoria']);

        if ($request->has('estado')) {
            $query->whereHas('estado', function ($q) use ($request) {
                $q->where('nombre', $request->estado);
            });
        }

        if ($request->has('orden') && in_array($request->orden, ['asc', 'desc'])) {
            $query->orderBy('fecha_inicio', $request->orden);
        } else {
            $query->orderBy('fecha_inicio', 'desc'); // Orden por defecto
        }

        $incidencias = $query->get();

        return view('dashboard.cliente', compact('incidencias'));
    }

    public function cerrarIncidencia($id)
    {
        $incidencia = Incidencia::where('id', $id)
                                ->where('id_cliente', Auth::id())
                                ->whereHas('estado', function ($q) {
                                    $q->where('nombre', 'Resuelta'); // Aquí estaba el error
                                })
                                ->firstOrFail();

        // Obtener el ID del estado "Tancada"
        $estadoTancada = EstadoIncidencia::where('nombre', 'Tancada')->firstOrFail();

        $incidencia->id_estado = $estadoTancada->id; // Cambia al estado "Tancada"
        $incidencia->fecha_final = Carbon::now();
        $incidencia->save();

        return redirect()->route('dashboard.cliente')->with('success', 'Incidencia cerrada correctamente.');
    }

    public function crearIncidencia(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'id_subcategoria' => 'required|exists:subcategoria,id',
            'id_prioridad' => 'required|exists:prioridad,id'
        ]);

        $estadoInicial = EstadoIncidencia::where('nombre', 'Sin asignar')->firstOrFail();

        Incidencia::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'id_cliente' => Auth::id(),
            'id_estado' => $estadoInicial->id,
            'id_subcategoria' => $request->id_subcategoria,
            'id_prioridad' => $request->id_prioridad,
            'fecha_inicio' => Carbon::now(),
            'img' => $request->img ?? null
        ]);

        return redirect()->route('dashboard.cliente')->with('success', 'Incidencia registrada correctamente.');
    }
}
