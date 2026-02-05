<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
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

            //si el usuario creado es un paciente, envia el modulo paciente
            if ($user::role('Paciente')) {
                //creamos el registro de paciente 
                $patient = $user->patient()->create([]);
                return redirect()->route('admin.patients.edit', $patient);
            }

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado exitosamente.');


    }

    /**
     * Muestra el formulario para editar un usuario existente.
     */
    public function edit(User $user)
    {
        $roles= Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Actualiza un usuario existente (temporalmente vacío).
     */
    public function update(Request $request, User $user)
{
    $data = $request->validate([
        'name' => 'required|string|min:3|max:255',
        'email' => 'required|string|email|unique:users,email,'. $user->id,
        'id_number' => 'required|string|min:5|max:20|regex:/^[A-Za-z0-9\-]+$/|unique:users,id_number,'. $user->id,
        'phone' => 'required|digits_between:7,15',
        'address' => 'required|string|min:3|max:255',
        'role_id'=>'required|exists:roles,id',
    ]);

    $user->update($data);

    if ($request->filled('password')) {
        $user->password = bcrypt($request->password);
        $user->save();
    }

    $user->roles()->sync($data['role_id']);

    session()->flash('swal', [
        'icon' => 'success',
        'title' => 'Usuario actualizado',
        'text' => 'El usuario ha sido actualizado exitosamente.',
    ]);

    return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado exitosamente.');
}


    /**
     * Elimina un usuario (temporalmente vacío).
     */
    public function destroy(User $user)
    {
        //No permitir que un usuario se elimine a sí mismo
        if (Auth::user()->id === $user->id) {
             session()->flash('swal', [
            'icon' => 'error',
            'title' => 'Usuario no eliminado',
            'text' => 'El usuario no se puede eliminar',
            ]);
            abort(403, 'No puedes eliminarte a ti mismo.');
        }

        //Eliminar roles asocioados a un usuario
        $user->roles()->detach();

        //Eliminar usuario
        $user->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario eliminado',
            'text' => 'El usuario ha sido eliminado exitosamente.',
            ]);

        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado exitosamente.');
    }

}