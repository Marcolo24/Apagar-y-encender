<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoIncidenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('estado_incidencia')->insert([
            ['id' => 1, 'nombre' => 'Sin asignar'],
            ['id' => 2, 'nombre' => 'Asignada'],
            ['id' => 3, 'nombre' => 'En trabajo'],
            ['id' => 4, 'nombre' => 'Resuelta'],
            ['id' => 5, 'nombre' => 'Cerrada'],
        ]);
    }
}