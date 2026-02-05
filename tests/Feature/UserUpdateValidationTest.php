<?php

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('no se puede actualizar un usuario con datos invalidos', function () {
    $admin = User::factory()->create();
    $role = Role::factory()->create();

    $user = User::factory()->create([
        'name' => 'Nombre Original',
        'phone' => '1234567890',
        'address' => 'Direccion original',
    ]);

    $this->actingAs($admin);

    $response = $this->put(route('admin.users.update', $user->id), [
        // name vacío e email inválido
        'name' => '',
        'email' => 'correo-no-valido',
        'id_number' => $user->id_number,
        'phone' => '',
        'address' => '',
        'role_id' => $role->id,
    ]);

    // Debe regresar con errores de validación
    $response->assertSessionHasErrors(['name', 'email', 'phone', 'address']);

    // El usuario NO debe cambiar en BD
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Nombre Original',
    ]);
});
