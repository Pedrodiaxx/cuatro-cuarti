<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Contracts\Role as ContractsRole;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Definir roles
        $roles = [
            'Paciente',
            'Doctor',
            'Recepcionista',
            'Administrador',
        ];
        //Crear roles
        foreach ($roles as $role) {
            Role::create([
                'name' => $role,
            ]);
        }
    }   
}
