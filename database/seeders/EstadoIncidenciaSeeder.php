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
            ['nombre' => 'Abierto'],
            ['nombre' => 'En Progreso'],
            ['nombre' => 'Cerrado'],
        ]);
    }
} 