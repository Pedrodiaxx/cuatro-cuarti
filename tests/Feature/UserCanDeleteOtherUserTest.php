<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('un usuario autenticado puede eliminar a otro usuario', function () {
    $admin = User::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($admin);

    $response = $this->delete(route('admin.users.destroy', $user->id));

    $response->assertRedirect();

    $this->assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);
});
