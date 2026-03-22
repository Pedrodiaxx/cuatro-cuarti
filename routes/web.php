<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin'); // Redirige al admin por defecto

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard de administrador
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); // vista en resources/views/admin/dashboard.blade.php
    })->name('dashboard');

    Route::resource('feedbacks', \App\Http\Controllers\FeedbackController::class)->except(['show', 'destroy', 'edit', 'update']);

    // Custom Appointment creation route (Livewire)
    Route::get('/appointments/create', \App\Livewire\Admin\AppointmentManager::class)->name('appointments.create');
    
    // Appointments (Resource without create and store, since Livewire will handle it)
    Route::resource('appointments', \App\Http\Controllers\Admin\AppointmentController::class)
        ->except(['create', 'store']);
        
    // Consultation Manager
    Route::get('/appointments/{appointment}/consultation', \App\Livewire\Admin\ConsultationManager::class)->name('appointments.consultation');
    
    // Doctor Schedules
    Route::get('/doctors/{doctor}/schedule', \App\Livewire\Admin\DoctorScheduleManager::class)->name('doctors.schedule');
    
    Route::get('/preview-email', function () {
        $appointment = \App\Models\Appointment::with(['patient.user', 'doctor.user', 'doctor.speciality'])->latest()->first();
        if (!$appointment) {
            return "No hay citas creadas para visualizar el correo.";
        }
        return new \App\Mail\AppointmentConfirmed($appointment);
    });
});
