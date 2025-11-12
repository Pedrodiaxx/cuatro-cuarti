<?php

use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;




Route::get('/', function(){
    return view ('admin.dashboard');
})->name('dashboard');

//Gestión de Roles
Route::resource('roles',RoleController::class);

//Gestión de Usuarios
Route::resource('users', UserController::class);

Route::get('/gestion', function () {
    return view('admin.gestion.index');
})->name('admin.gestion');
