<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SedeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Deshabilitar las verificaciones de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncar la tabla
        DB::table('sede')->truncate();

        // Habilitar las verificaciones de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Inserta nuevos registros
        DB::table('sede')->insert([
            ['id' => 1, 'nombre' => 'Central'],
            ['id' => 2, 'nombre' => 'Sucursal Norte'],
            ['id' => 3, 'nombre' => 'Sucursal Sur'],
        ]);
    }
} 