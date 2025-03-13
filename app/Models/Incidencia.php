<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EstadoIncidencia;
use App\Models\Prioridad;

class Incidencia extends Model
{
    protected $table = 'incidencia';

    public function cliente()
    {
        return $this->belongsTo(User::class, 'id_cliente');
    }

    public function tecnico()
    {
        return $this->belongsTo(User::class, 'id_tecnico');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoIncidencia::class, 'id_estado');
    }

    public function prioridad()
    {
        return $this->belongsTo(Prioridad::class, 'id_prioridad');
    }
}
