<?php
namespace App\Http\Controllers;

use App\Models\Incidencia;
use Illuminate\Http\Request;

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
        $incidencia = Incidencia::find($id);
        if ($incidencia->estado->nombre == 'Asignada') {
            $incidencia->id_estado = 3; // Cambiar a "En treball"
            $incidencia->save();
        }
        return redirect()->route('dashboard.tecnico');
    }

    public function resolver($id)
    {
        $incidencia = Incidencia::find($id);
        if ($incidencia->estado->nombre == 'En treball') {
            $incidencia->id_estado = 4; // Cambiar a "Resolta"
            $incidencia->save();
        }
        return redirect()->route('dashboard.tecnico');
    }

    public function cerrar($id)
    {
        $incidencia = Incidencia::find($id);
        // Lógica para cerrar la incidencia
        return redirect()->route('dashboard.tecnico');
    }

    public function mensaje($id)
    {
        // Lógica para enviar un mensaje al cliente
        return redirect()->route('dashboard.tecnico');
    }
}
