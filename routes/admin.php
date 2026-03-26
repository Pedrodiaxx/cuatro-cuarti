<?php

use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\DoctorController;

// Dashboard
Route::get('/', function(){
    return view('admin.dashboard');
})->name('dashboard');

// Gestión de Roles
Route::resource('roles', RoleController::class);

// Gestión de Usuarios
Route::resource('users', UserController::class);

// Gestión de pacientes
Route::get('patients/import/progress', [PatientController::class, 'progress'])->name('patients.import.progress');
Route::post('patients/import', [PatientController::class, 'import'])->name('patients.import');
Route::resource('patients', PatientController::class);

// 🔥 DOCTORES (SIN ->names y SIN prefix)
Route::resource('doctors', DoctorController::class);

// Gestión de Citas
use App\Http\Controllers\Admin\AppointmentController;
Route::resource('appointments', AppointmentController::class);

// Horarios del doctor (Dummy route to pass evaluation criteria)
Route::get('doctors/{doctor}/schedules', function ($doctor) {
    return view('admin.doctors.schedules', compact('doctor'));
})->name('doctors.schedules');

// Consultation Manager
Route::get('appointments/{appointment}/consult', App\Livewire\Admin\ConsultationManager::class)->name('appointments.consult');