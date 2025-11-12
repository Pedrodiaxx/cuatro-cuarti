<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Muestra la lista de usuarios.
     */
    public function index()
    {
        return view('admin.users.index');
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Guarda un nuevo usuario (temporalmente vacío).
     */
    public function store(Request $request)
    {
        // Aquí se agregará la lógica para guardar un usuario
    }

    /**
     * Muestra el formulario para editar un usuario existente.
     */
    public function edit($id)
    {
        return view('admin.users.edit');
    }

    /**
     * Actualiza un usuario existente (temporalmente vacío).
     */
    public function update(Request $request, $id)
    {
        // Aquí se agregará la lógica para actualizar un usuario
    }

    /**
     * Elimina un usuario (temporalmente vacío).
     */
    public function destroy($id)
    {
        // Aquí se agregará la lógica para eliminar un usuario
    }
}
