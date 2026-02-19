<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Speciality;

class SpecialitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    //Su función técnica es la población inicial de datos (Data Seeding) en la base de datos
   public function run(): void
{
    $items = [
        'Cardiología',
        'Pediatría',
        'Dermatología',
        'Ginecología',
        'Traumatología',
        'Neurología',
        'Oftalmología',
        'Medicina Interna',
    ];
    //Primero busca en la tabla specialities si ya existe un registro donde la columna name coincida con el valor del array
    foreach ($items as $name) {
        Speciality::firstOrCreate(['name' => $name]);
    }
}
}
