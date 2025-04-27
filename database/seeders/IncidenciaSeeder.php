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
            // Incidencia para cliente sede 1
            [
                'id_cliente' => 4,
                'id_tecnico' => 2,
                'id_estado' => 1,
                'id_subcategoria' => 2,
                'id_prioridad' => 1,
                'titulo' => 'Problema de conexión',
                'descripcion' => 'El cliente no puede conectarse a la red WiFi.',
                'fecha_inicio' => now(),
                'fecha_final' => now(),
                'img' => 'incidencia1.png',
            ],
            // Incidencia para cliente sede 2
            [
                'id_cliente' => 7,
                'id_tecnico' => 6,
                'id_estado' => 1,
                'id_subcategoria' => 2,
                'id_prioridad' => 2,
                'titulo' => 'Problema de impresora',
                'descripcion' => 'La impresora no responde y no se puede imprimir.',
                'fecha_inicio' => now(),
                'fecha_final' => now(),
                'img' => 'incidencia2.png',
            ],
            // Incidencia para cliente sede 3
            [
                'id_cliente' => 10,
                'id_tecnico' => 9,
                'id_estado' => 1,
                'id_subcategoria' => 3,
                'id_prioridad' => 3,
                'titulo' => 'Fallo de sistema',
                'descripcion' => 'El sistema operativo no arranca.',
                'fecha_inicio' => now(),
                'fecha_final' => now(),
                'img' => 'incidencia3.png',
            ],
        ]);
    }
}
