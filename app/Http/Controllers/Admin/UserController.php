<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Doctor;

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
        'phone' => 'required|digits_between:7,15',
        'address' => 'required|string|min:3|max:255',
        'role_id' => 'required|exists:roles,id',
    ]);

    // 1) Guardar role_id y quitarlo del array del user
    $roleId = $data['role_id'];
    unset($data['role_id']);

    // 2) Hashear password
    $data['password'] = Hash::make($data['password']);

    // 3) Crear usuario
    $user = User::create($data);

    // 4) Asignar rol
    $user->roles()->attach($roleId);

    session()->flash('swal', [
        'icon' => 'success',
        'title' => 'Usuario creado',
        'text' => 'El usuario ha sido creado exitosamente.',
    ]);

    // 5) Crear módulo automático según rol
    $roleName = Role::find($roleId)->name;

    if ($roleName === 'Paciente') {
        $patient = $user->patient()->firstOrCreate([]);
        return redirect()->route('admin.patients.edit', $patient);
    }

    if ($roleName === 'Doctor') {
        $doctor = $user->doctor()->firstOrCreate([]);
        return redirect()->route('admin.doctors.edit', $doctor);
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

    $roleName = Role::find($data['role_id'])->name;

if ($roleName === 'Paciente') {
    $patient = $user->patient()->firstOrCreate([]);
    return redirect()->route('admin.patients.edit', $patient);
}

if ($roleName === 'Doctor') {
    $doctor = $user->doctor()->firstOrCreate([]);
    return redirect()->route('admin.doctors.edit', $doctor);
}

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