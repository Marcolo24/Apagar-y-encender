<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Incidencia;
use App\Models\EstadoIncidencia;
use App\Models\Prioridad;
use App\Models\Subcategoria;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->id_rol != 4) {
            return redirect()->route('index')->withErrors(['access' => 'No tienes permiso para acceder aquí.']);
        }
    
        $cliente = Auth::user(); // Obtener usuario autenticado
    
        // Obtener solo las subcategorías que pertenecen a la sede del cliente
        // Esta consulta busca categorías que contengan subcategorías, las cuales a su vez tienen incidencias registradas por clientes de la misma sede que el usuario autenticado.
        $subcategorias = Subcategoria::whereHas('categoria', function ($query) use ($cliente) {
            $query->whereHas('subcategorias', function ($q) use ($cliente) {
                $q->whereHas('incidencias', function ($inc) use ($cliente) {
                    $inc->whereHas('cliente', function ($user) use ($cliente) {
                        $user->where('id_sede', $cliente->id_sede);
                    });
                });
            });
        })->get();
    
        $prioridades = Prioridad::all();
    
        // Construcción de la consulta para incidencias del cliente
        $query = Incidencia::where('id_cliente', $cliente->id)
                            ->with(['estado', 'prioridad', 'subcategoria']);
    
        // Filtro de estado si se solicita
        if ($request->has('estado')) {
            $query->whereHas('estado', function ($q) use ($request) {
                $q->where('nombre', $request->estado);
            });
        }
    
        // Ocultar incidencias resueltas si el usuario lo desea
        if ($request->has('ocultar_resueltas') && $request->ocultar_resueltas) {
            $query->whereHas('estado', function ($q) {
                $q->where('nombre', '!=', 'Resuelta');
            });
        }
    
        // Ordenar por fecha de inicio
        $query->orderBy('fecha_inicio', $request->get('orden', 'desc'));
    
        $incidencias = $query->get();
    
        return view('dashboard.cliente', compact('incidencias', 'prioridades', 'subcategorias'));
    }    

    public function cerrarIncidencia($id)
    {
        try {
            $incidencia = Incidencia::where('id', $id)
                ->where('id_cliente', Auth::id())
                ->with('estado')
                ->firstOrFail();

            if ($incidencia->estado->nombre !== 'Resuelta') {
                return response()->json([
                    'success' => false,
                    'message' => 'La incidencia debe estar resuelta para poder cerrarla'
                ], 400);
            }

            $estadoCerrada = EstadoIncidencia::where('nombre', 'Cerrada')->firstOrFail();

            $incidencia->id_estado = $estadoCerrada->id;
            $incidencia->fecha_final = now();
            $incidencia->save();

            return response()->json([
                'success' => true,
                'message' => 'Incidencia cerrada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cerrar la incidencia: ' . $e->getMessage()
            ], 500);
        }
    }

    public function crearIncidencia(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'id_subcategoria' => 'required|exists:subcategoria,id',
            'id_prioridad' => 'required|exists:prioridad,id',
            'img' => 'nullable|image|max:2048' // Permitir imágenes de hasta 2MB
        ]);

        $cliente = Auth::user(); // Obtener el cliente autenticado

        // Obtener el estado inicial "Sin asignar"
        $estadoInicial = EstadoIncidencia::where('nombre', 'Sin asignar')->firstOrFail();

        // Procesar la imagen si se ha subido una
        $rutaImagen = null;
        if ($request->hasFile('img')) {
            $imagen = $request->file('img');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $rutaImagen = $imagen->storeAs('public/incidencias', $nombreImagen);
        }

        $incidencia = Incidencia::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'id_cliente' => $cliente->id,
            'id_estado' => $estadoInicial->id,
            'id_subcategoria' => $request->id_subcategoria,
            'id_prioridad' => $request->id_prioridad,
            'fecha_inicio' => now(),
            'id_tecnico' => null,
            'img' => $rutaImagen
        ]);

        return response()->json([
            'message' => 'Incidencia registrada correctamente.',
            'incidencia' => $incidencia->load('prioridad', 'estado', 'cliente.sede')
        ]);
    }

    public function detalleIncidencia($id)
    {
        $incidencia = Incidencia::where('id', $id)
            ->where('id_cliente', Auth::id())
            ->with(['estado', 'prioridad', 'subcategoria'])
            ->firstOrFail();

        return view('dashboard.detalle-incidencia', compact('incidencia'));
    }
}
