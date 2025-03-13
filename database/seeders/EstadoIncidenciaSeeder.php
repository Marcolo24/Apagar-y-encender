<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoIncidenciaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('estado_incidencia')->insert([
            ['nombre' => 'Sin asignar'],
            ['nombre' => 'Asignada'],
            ['nombre' => 'En trabajo'],
            ['nombre' => 'Resuelta'],
            ['nombre' => 'Cerrada'],
        ]);
    }
}
