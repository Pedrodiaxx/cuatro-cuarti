<?php

use App\Models\User;
Use Illuminate\Foundation\Testing\RefreshDatabase;


//usar la funcion para refrescar la base de datos
Uses(RefreshDatabase::class);

test('un usuario no puede eliminarse a sis mismo', function () {
    //1)Crear un usuario de prueba
    $user = User::factory()->create();

    // 2) Similiar que ese usuario ya inicio sesion
    $this->actingAs($user, 'web');

    //3) simular una peticion HTTP DELETE (borrar un usuario)
    $response = $this->delete(route('admin.users.destroy', $user));

    // 4)Esperar que el servidor bloquee el borrado a si mismo
    $response->assertStatus (403);

    //5) Verificar que el usuario aún exista en la base de datos
    $this->assertDatabaseHas('users', [
        'id' => $user->id]);
});