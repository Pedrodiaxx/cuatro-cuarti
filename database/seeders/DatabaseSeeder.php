<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // llammar al RoleSeeder creado
        $this->call([
            RoleSeeder::class]);

        // User::factory(10)->create();

        // Crear un usuario de prueba
        User::factory()->create([
            'name' => 'Pedro',
            'email' => 'joel.diaz.lopez7@gmail.com',
            'password' => bcrypt('12345678'),

        ]);
    }
}
