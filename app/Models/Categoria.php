<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categoria'; // Nombre de la tabla en la base de datos

    protected $fillable = ['nombre'];

    public $timestamps = false; // No necesitamos `created_at` ni `updated_at`

    /**
     * Relación con Subcategoria (1 categoría tiene muchas subcategorías)
     */
    public function subcategorias()
    {
        return $this->hasMany(Subcategoria::class, 'id_categoria');
    }

    /**
     * Relación con Incidencia a través de Subcategoria
     */
    public function incidencias()
    {
        return $this->hasManyThrough(
            Incidencia::class,    // Modelo final
            Subcategoria::class,  // Modelo intermedio
            'id_categoria',       // Clave foránea en `subcategoria`
            'id_subcategoria',    // Clave foránea en `incidencia`
            'id',                 // Clave primaria en `categoria`
            'id'                  // Clave primaria en `subcategoria`
        );
    }
}
