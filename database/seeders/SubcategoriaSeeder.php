<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubcategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subcategoria')->insert([
            ['id_categoria' => 1, 'nombre' => 'Aplicaciones'],
            ['id_categoria' => 2, 'nombre' => 'Periféricos'],
            ['id_categoria' => 3, 'nombre' => 'WiFi'],
        ]);
    }
} 