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

        foreach ($items as $name) {
            Speciality::firstOrCreate([
                'name' => $name
            ]);
        }
    }
}