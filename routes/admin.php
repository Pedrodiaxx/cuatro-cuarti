<?php

use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\DoctorController;




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

//Gestion de pacientes
Route::get('patients/import-status', [PatientController::class, 'importStatus'])->name('patients.import.status');
Route::post('patients/import', [PatientController::class, 'import'])->name('patients.import');
Route::resource('patients', PatientController::class);

//Gestion de doctores
Route::resource('doctors', DoctorController::class);
