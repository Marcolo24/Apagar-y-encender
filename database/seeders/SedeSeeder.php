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
        DB::table('sede')->insert([
            ['nombre' => 'Central'],
            ['nombre' => 'Sucursal Norte'],
            ['nombre' => 'Sucursal Sur'],
        ]);
    }
} 