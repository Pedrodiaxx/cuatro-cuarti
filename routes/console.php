<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Mail;
use App\Models\Appointment;
use App\Mail\DailyAppointmentsReportMail;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $appointments = Appointment::with(['patient.user', 'doctor.user'])
        ->whereDate('date', now()->toDateString())
        ->orderBy('start_time')
        ->get();

    // Enviar reporte completo al administrador
    Mail::to('admin@example.com')->send(new DailyAppointmentsReportMail($appointments, 'Administrador'));

    // Agrupar citas por doctor para enviar sus reportes individuales
    $appointmentsByDoctor = $appointments->groupBy('doctor_id');

    foreach ($appointmentsByDoctor as $doctorId => $doctorAppointments) {
        sleep(2); // Evitar el límite de peticiones de Mailtrap
        
        $doctor = $doctorAppointments->first()->doctor;
        if ($doctor && $doctor->user && $doctor->user->email) {
            Mail::to($doctor->user->email)->send(
                new DailyAppointmentsReportMail($doctorAppointments, 'Dr(a). ' . $doctor->user->name)
            );
        }
    }
})->dailyAt('08:00');
