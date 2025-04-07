<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prioridad extends Model
{
    use HasFactory;

    protected $table = 'prioridad';

    protected $fillable = ['nombre'];

    public $timestamps = false;

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'id_prioridad');
    }
}
