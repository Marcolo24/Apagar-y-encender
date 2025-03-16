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

    protected $table = 'incidencia'; // Asegúrate de que el nombre de la tabla es correcto

    protected $fillable = [
        'titulo', 
        'descripcion', 
        'id_cliente', 
        'id_tecnico', 
        'id_estado',
        'id_subcategoria', 
        'id_prioridad', 
        'fecha_inicio', 
        'fecha_final', 
        'img',
    ];

    public $timestamps = false;

    // Relación con el usuario (cliente)
    public function cliente()
    {
        return $this->belongsTo(User::class, 'id_cliente');
    }

    // Relación con el usuario (técnico)
    public function tecnico()
    {
        return $this->belongsTo(User::class, 'id_tecnico');
    }

    // Relación con el estado de la incidencia
    public function estado()
    {
        return $this->belongsTo(EstadoIncidencia::class, 'id_estado');
    }

    // Relación con la prioridad
    public function prioridad()
    {
        return $this->belongsTo(Prioridad::class, 'id_prioridad');
    }

    // Relación con la subcategoría
    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class, 'id_subcategoria');
    }

    // Relación con la sede
    public function sede()
    {
        return $this->hasOneThrough(Sede::class, User::class, 'id', 'id', 'id_cliente', 'id_sede');
    }
}