<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Incidencia;
use App\Models\EstadoIncidencia;
use App\Models\Prioridad;
use App\Models\Subcategoria;
use Carbon\Carbon;

class ClienteController extends Controller
{
    public function index(Request $request)
{
    if (Auth::user()->id_rol != 4) {
        return redirect()->route('index')->withErrors(['access' => 'No tienes permiso para acceder aquí.']);
    }

    $query = Incidencia::where('id_cliente', Auth::id())->with(['estado', 'prioridad', 'subcategoria']);

    if ($request->has('estado')) {
        $query->whereHas('estado', function ($q) use ($request) {
            $q->where('nombre', $request->estado);
        });
    }

    if ($request->has('orden') && in_array($request->orden, ['asc', 'desc'])) {
        $query->orderBy('fecha_inicio', $request->orden);
    } else {
        $query->orderBy('fecha_inicio', 'desc');
    }

    $incidencias = $query->get();
    $prioridades = \App\Models\Prioridad::all();
    $subcategorias = \App\Models\Subcategoria::all();

    return view('dashboard.cliente', compact('incidencias', 'prioridades', 'subcategorias'));
}

    public function cerrarIncidencia($id)
    {
        $incidencia = Incidencia::where('id', $id)
                                ->where('id_cliente', Auth::id())
                                ->whereHas('estado', function ($q) {
                                    $q->where('nombre', 'Resuelta');
                                })
                                ->firstOrFail();

        $estadoTancada = EstadoIncidencia::where('nombre', 'Tancada')->firstOrFail();

        $incidencia->id_estado = $estadoTancada->id;
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

        $incidencia = Incidencia::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'id_cliente' => Auth::id(),
            'id_estado' => $estadoInicial->id,
            'id_subcategoria' => $request->id_subcategoria,
            'id_prioridad' => $request->id_prioridad,
            'fecha_inicio' => Carbon::now(),
            'img' => $request->img ?? null
        ]);

        return response()->json([
            'message' => 'Incidencia registrada correctamente.',
            'incidencia' => $incidencia->load('prioridad', 'estado')
        ]);
    }
}
