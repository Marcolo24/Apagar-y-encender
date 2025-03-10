<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id_rol' => 1,
                'id_sede' => 1,
                'id_estado_usuario' => 1,
                'name' => 'Juan',
                'apellidos' => 'Pérez',
                'correo' => 'juan.perez@example.com',
                'password' => Hash::make('qweQWE123'),
                'email' => 'juan.perez@example.com',
            ],
            // Agrega más usuarios si es necesario
        ]);
    }
} 