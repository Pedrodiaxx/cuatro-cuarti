<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('un usuario autenticado puede actualizar a otro usuario', function () {
    $admin = User::factory()->create();

    $role = \App\Models\Role::factory()->create();

    $user = User::factory()->create([
        'name' => 'Nombre Original',
        'phone' => '1234567890',
        'address' => 'Direccion original',
    ]);

    $this->actingAs($admin);

    $response = $this->put(route('admin.users.update', $user->id), [
        'name' => 'Nombre Actualizado',
        'email' => $user->email,
        'id_number' => $user->id_number,
        'phone' => '999888777',
        'address' => 'Nueva direccion',
        'role_id' => $role->id,
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Nombre Actualizado',
    ]);
});
