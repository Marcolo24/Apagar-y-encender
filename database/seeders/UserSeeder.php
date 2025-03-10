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
                'name' => 'Admin',
                'apellidos' => 'Admin',
                'correo' => 'admin@example.com',
                'password' => Hash::make('qweQWE123'),
                'email' => 'admin@example.com',
            ],
            [
                'id_rol' => 2,
                'id_sede' => 1,
                'id_estado_usuario' => 1,
                'name' => 'Tecnico',
                'apellidos' => 'Tecnico',
                'correo' => 'tecnico@example.com',
                'password' => Hash::make('qweQWE123'),
                'email' => 'tecnico@example.com',
            ],
            [
                'id_rol' => 3,
                'id_sede' => 1,
                'id_estado_usuario' => 1,
                'name' => 'Gestor',
                'apellidos' => 'Gestor',
                'correo' => 'gestor@example.com',
                'password' => Hash::make('qweQWE123'),
                'email' => 'gestor@example.com',
            ],
            [
                'id_rol' => 4,
                'id_sede' => 1,
                'id_estado_usuario' => 1,
                'name' => 'Cliente',
                'apellidos' => 'Cliente',
                'correo' => 'cliente@example.com',
                'password' => Hash::make('qweQWE123'),
                'email' => 'cliente@example.com',
            ],
        ]);
    }
}