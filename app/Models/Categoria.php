<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categoria';
    public $timestamps = false;

    protected $fillable = [
        'nombre'
    ];

    /**
     * Obtiene las subcategorías de esta categoría
     */
    public function subcategorias()
    {
        return $this->hasMany(Subcategoria::class, 'id_categoria');
    }
}
