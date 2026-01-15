<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
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
        $roles= Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Guarda un nuevo usuario (temporalmente vacío).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'id_number' => 'required|string|min:5|max:20|regex:/^[A-Za-z0-9\-]+$/|unique:users',
            'phone' => 'required|digits between:7,15',
            'address' => 'required|string|min:3|max:255',
            'role_id'=>'required|exists:roles,id',
        ]);

        $user = User::create($data);

        $user->roles()->attach($data['role_id']);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario creado',
            'text' => 'El usuario ha sido creado exitosamente.',
            ]);

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado exitosamente.');


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
