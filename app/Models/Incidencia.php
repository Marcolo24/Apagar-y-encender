<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Importar User
use App\Models\Prioridad; // Importar Prioridad
use App\Models\Estado; // Importar Estado

class Incidencia extends Model
{
    use HasFactory;

    protected $table = 'incidencia';
    public $timestamps = false; // No gestionamos las fechas 'created_at' y 'updated_at'

    // Relación con el modelo User (cliente)
    public function cliente()
    {
        return $this->belongsTo(User::class, 'id_cliente');
    }

    // Relación con el modelo Prioridad
    public function prioridad()
    {
        return $this->belongsTo(Prioridad::class, 'id_prioridad');
    }

    // Relación con el modelo Estado (estado de la incidencia)
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id_estado'); // Relacionamos con la columna id_estado
    }

    // Relación con el técnico asignado (si existe)
    public function tecnico()
    {
        return $this->belongsTo(User::class, 'id_tecnico');
    }
}
