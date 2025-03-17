<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    use HasFactory;

    protected $table = 'subcategoria';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'id_categoria'
    ];

    /**
     * Obtiene la categoría padre
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }
} 