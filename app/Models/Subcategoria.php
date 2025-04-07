<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    use HasFactory;

    protected $table = 'subcategoria';

    protected $fillable = ['nombre', 'id_categoria'];

    public $timestamps = false;

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'id_subcategoria');
    }
}