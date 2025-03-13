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
            ['id' => 1, 'nombre' => 'Sense assignar'],
            ['id' => 2, 'nombre' => 'Assignada'],
            ['id' => 3, 'nombre' => 'En treball'],
            ['id' => 4, 'nombre' => 'Resoluta'],
            ['id' => 5, 'nombre' => 'Tancada'],
        ]);
    }
} 