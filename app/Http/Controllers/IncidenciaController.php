<?php
namespace App\Http\Controllers;

use App\Models\Incidencia;
use Illuminate\Http\Request;
use App\Models\EstadoIncidencia;
use App\Models\Prioridad;

class IncidenciaController extends Controller
{
    public function index()
    {
        $incidencias = Incidencia::with(['cliente', 'tecnico', 'estado', 'prioridad'])->get();
        $prioridades = Prioridad::all();
        $estados = EstadoIncidencia::all();

        return view('dashboard.tecnico', compact('incidencias', 'prioridades', 'estados'));
    }

    // Métodos para las acciones
    public function asignar($id)
    {
        $incidencia = Incidencia::find($id);
        // Lógica para asignar la incidencia
        return redirect()->route('dashboard.tecnico');
    }

    public function desasignar($id)
    {
        $incidencia = Incidencia::find($id);
        // Lógica para desasignar la incidencia
        return redirect()->route('dashboard.tecnico');
    }

    public function empezar($id)
    {
        $incidencia = Incidencia::findOrFail($id);
        $estadoEnTrabajo = EstadoIncidencia::where('nombre', 'En trabajo')->firstOrFail();
        $incidencia->id_estado = $estadoEnTrabajo->id;
        $incidencia->save();

        return response()->json(['success' => true, 'message' => 'Incidencia comenzada.']);
    }

    public function resolver($id)
    {
        $incidencia = Incidencia::findOrFail($id);
        $estadoResuelta = EstadoIncidencia::where('nombre', 'Resuelta')->firstOrFail();
        $incidencia->id_estado = $estadoResuelta->id;
        $incidencia->save();

        return redirect()->route('dashboard.tecnico')->with('success', 'Incidencia resuelta.');
    }

    public function cerrar($id)
    {
        $incidencia = Incidencia::find($id);
        // Lógica para cerrar la incidencia
        return redirect()->route('dashboard.tecnico');
    }

    public function mensaje($id)
    {
        // Implementa la lógica para enviar un mensaje al cliente
        return view('incidencias.mensaje', compact('id'));
    }

    public function enviarMensaje(Request $request, $id)
    {
        // Lógica para enviar el mensaje al cliente
        // Podrías guardar el mensaje en la base de datos o enviarlo por correo electrónico

        return redirect()->route('dashboard.tecnico')->with('success', 'Mensaje enviado.');
    }

    public function buscarIncidencias(Request $request){
    $query = Incidencia::with(['cliente', 'estado', 'prioridad']);

    if ($request->filled('nombre')) {
        $query->where('titulo', 'like', '%' . $request->nombre . '%');
    }

    if ($request->filled('estado')) {
        $query->where('id_estado', $request->estado);
    }

    if ($request->filled('prioridad')) {
        $query->where('id_prioridad', $request->prioridad);
    }

    $incidencias = $query->orderBy($request->orderBy, $request->orderDirection)->get();

    return response()->json($incidencias);
}
}
