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
                'name' => 'TecnicoCentral',
                'apellidos' => 'TecnicoCentral',
                'correo' => 'tecnicocentral@example.com',
                'password' => Hash::make('qweQWE123'),
                'email' => 'tecnicocentral@example.com',
            ],
            [
                'id_rol' => 3,
                'id_sede' => 1,
                'id_estado_usuario' => 1,
                'name' => 'GestorCentral',
                'apellidos' => 'GestorCentral',
                'correo' => 'gestorcentral@example.com',
                'password' => Hash::make('qweQWE123'),
                'email' => 'gestorcentral@example.com',
            ],
            [
                'id_rol' => 4,
                'id_sede' => 1,
                'id_estado_usuario' => 1,
                'name' => 'ClienteCentral',
                'apellidos' => 'Cliente Central',
                'correo' => 'clientecentral@example.com',
                'password' => Hash::make('qweQWE123'),
                'email' => 'clientecentral@example.com',
            ],

            // Usuarios sede 2
            [
                'id_rol' => 3,
                'id_sede' => 2,
                'id_estado_usuario' => 1,
                'name' => 'GestorNorte',
                'apellidos' => 'Gestor Sede Norte',
                'correo' => 'gestornorte@example.com',
                'password' => Hash::make('qweQWE123'),
                'email' => 'gestornorte@example.com',
            ],
            [
                'id_rol' => 2,
                'id_sede' => 2,
                'id_estado_usuario' => 1,
                'name' => 'TecnicoNorte',
                'apellidos' => 'Tecnico Sede Norte',
                'correo' => 'tecniconorte@example.com',
                'password' => Hash::make('qweQWE123'),
                'email' => 'tecniconorte@example.com',
            ],
            [
                'id_rol' => 4,
                'id_sede' => 2,
                'id_estado_usuario' => 1,
                'name' => 'ClienteNorte',
                'apellidos' => 'Cliente Sede Norte',
                'correo' => 'clientenorte@example.com',
                'password' => Hash::make('qweQWE123'),
                'email' => 'clientenorte@example.com',
            ],

            // Usuarios sede 3
            [
                'id_rol' => 3,
                'id_sede' => 3,
                'id_estado_usuario' => 1,
                'name' => 'GestorSur',
                'apellidos' => 'Gestor Sede Sur',
                'correo' => 'gestorsur@example.com',
                'password' => Hash::make('qweQWE123'),
                'email' => 'gestorsur@example.com',
            ],
            [
                'id_rol' => 2,
                'id_sede' => 3,
                'id_estado_usuario' => 1,
                'name' => 'TecnicoSur',
                'apellidos' => 'Tecnico Sede Sur',
                'correo' => 'tecnicosur@example.com',
                'password' => Hash::make('qweQWE123'),
                'email' => 'tecnicosur@example.com',
            ],
            [
                'id_rol' => 4,
                'id_sede' => 3,
                'id_estado_usuario' => 1,
                'name' => 'ClienteSur',
                'apellidos' => 'Cliente Sede Sur',
                'correo' => 'clientesur@example.com',
                'password' => Hash::make('qweQWE123'),
                'email' => 'clientesur@example.com',
            ],
        ]);
    }
}