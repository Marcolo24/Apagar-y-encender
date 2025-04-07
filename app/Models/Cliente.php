<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente'; // Asegura que el nombre coincide con la BD
    protected $primaryKey = 'id'; // Define la clave primaria

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'id_cliente');
    }
}
