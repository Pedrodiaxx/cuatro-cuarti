<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('un usuario autenticado puede actualizar a otro usuario', function () {
    // Usuario que actuará como administrador
    $admin = User::factory()->create();

    // Usuario que será modificado
    $user = User::factory()->create([
        'name' => 'Nombre Original',
    ]);

    $this->actingAs($admin);

    $response = $this->put(route('admin.users.update', $user->id), [
        'name' => 'Nombre Actualizado',
        'email' => $user->email,
        'id_number' => $user->id_number,
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Nombre Actualizado',
    ]);
});
