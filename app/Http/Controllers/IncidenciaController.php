<?php
namespace App\Http\Controllers;

use App\Models\Incidencia;
use Illuminate\Http\Request;
use App\Models\EstadoIncidencia;

class IncidenciaController extends Controller
{
    public function index()
    {
        $incidencias = Incidencia::with(['cliente', 'tecnico', 'estado', 'prioridad'])->get();
        return view('dashboard.tecnico', compact('incidencias'));
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

        return redirect()->route('dashboard.tecnico')->with('success', 'Incidencia comenzada.');
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
}
