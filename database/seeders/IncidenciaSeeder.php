<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IncidenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('incidencia')->insert([
            [
                'id_cliente' => 1,
                'id_tecnico' => 2,
                'id_estado' => 1,
                'id_subcategoria' => 2,
                'id_prioridad' => 1,
                'titulo' => 'Problema de conexión',
                'descripcion' => 'No se puede conectar a la red WiFi.',
                'fecha_inicio' => now(),
                'fecha_final' => now(),
                'img' => 'imagen.png',
            ],
            [
                'id_cliente' => 2,
                'id_tecnico' => 3,
                'id_estado' => 2,
                'id_subcategoria' => 2,
                'id_prioridad' => 2,
                'titulo' => 'Error de hardware',
                'descripcion' => 'El disco duro no responde.',
                'fecha_inicio' => now()->subDays(1),
                'fecha_final' => now(),
                'img' => 'imagen2.png',
            ],
            // Agrega más incidencias si es necesario
        ]);
    }
}