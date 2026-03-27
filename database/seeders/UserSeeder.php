<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'prueba1@gmail.com'], // condición única
            [
                'name' => 'DRILLO',
                'password' => Hash::make('password'),
                'id_number' => '12345678',
                'phone' => '555-1234',
                'address' => 'calle 234, colonia 543',
            ]
        );
    }
}