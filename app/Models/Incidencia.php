<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // 🔹 Importar User
use App\Models\Prioridad;

class Incidencia extends Model
{
    use HasFactory;

    protected $table = 'incidencia';

    public function cliente()
    {
        return $this->belongsTo(User::class, 'id_cliente'); // 🔹 Relación con User
    }

    public function prioridad()
    {
        return $this->belongsTo(Prioridad::class, 'id_prioridad');
    }
}
