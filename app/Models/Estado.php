<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;

    // Definir la tabla si el nombre no sigue la convención
    protected $table = 'estado_incidencia';  // Suponiendo que esta es la tabla de los estados

    // Puedes definir relaciones si es necesario, por ejemplo, si un estado tiene muchas incidencias
    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'id_estado');  // Relación inversa
    }
}
