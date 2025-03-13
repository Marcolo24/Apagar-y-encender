<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Rol extends Model 
{

    protected $table = 'rol';

    // relacion con la tabla roles para que salga el nombre y no el id
    public function users()
    {
        return $this->hasMany(User::class, 'id_rol');
    }
}
