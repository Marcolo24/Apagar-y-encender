<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // 🔹 Importar User
use App\Models\Prioridad;

class Incidencia extends Model
{
    use HasFactory;

    protected $table = 'incidencia'; // Nombre real de la tabla en la BD

    protected $fillable = [
        'titulo', 'descripcion', 'id_cliente', 'id_tecnico', 'id_estado',
        'id_subcategoria', 'id_prioridad', 'fecha_inicio', 'fecha_final', 'img'
    ];

    public $timestamps = false; // No usamos `created_at` y `updated_at`

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
}
