<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoIncidencia extends Model
{
    use HasFactory;

    protected $table = 'estado_incidencia'; // Nombre de la tabla en la BD

    protected $fillable = ['nombre'];

    public $timestamps = false;

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'id_estado');
    }
}
