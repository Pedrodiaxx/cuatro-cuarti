<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear un usuario de prueba
        User::factory()->create([
            'name' => 'Pedro',
            'email' => 'joel.diaz.lopez7@gmail.com',
            'password' => bcrypt('12345678'),
            'id_number' => '12345678',
            'phone'=> '555-1234',
            'address'=> 'calle 234, colonia 543',
        ])->assignRole('doctor');
    }
}
