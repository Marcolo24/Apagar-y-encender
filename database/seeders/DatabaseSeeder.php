<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\CategoriaSeeder;
use Database\Seeders\RolSeeder;
use Database\Seeders\EstadoIncidenciaSeeder;
use Database\Seeders\SedeSeeder;
use Database\Seeders\EstadoUsuarioSeeder;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RolSeeder::class,
            SedeSeeder::class,
            EstadoUsuarioSeeder::class,
            CategoriaSeeder::class,
            EstadoIncidenciaSeeder::class,
            UserSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'apellidos' => 'Apellido de Prueba',
            'email' => 'test@example.com',
            'correo' => 'test@example.com',
            'id_rol' => 1,
            'id_sede' => 1,
            'id_estado_usuario' => 1,
        ]);
    }
}
