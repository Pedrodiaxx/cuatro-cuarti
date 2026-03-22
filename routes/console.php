<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Mail;
use App\Models\Appointment;
use App\Mail\DailyAdminReport;
use App\Mail\DailyDoctorReport;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $today = Carbon::today()->toDateString();
    
    // Get all appointments for today
    $appointments = Appointment::with(['patient.user', 'doctor.user', 'doctor.speciality'])
        ->where('date', $today)
        ->orderBy('start_time')
        ->get();

    // En entorno local (Resend Gratis), los correos solo llegan al dueño de la API Key.
    $validSandboxEmail = env('MAIL_FROM_ADDRESS', 'joel.diaz.lopez7@gmail.com');
    $apiKey = env('RESEND_API_KEY');

    // Send email to admin
    $htmlAdmin = (new DailyAdminReport($appointments))->render();
    \Illuminate\Support\Facades\Http::withToken($apiKey)->withoutVerifying()->post('https://api.resend.com/emails', [
        'from' => 'Pedrini Admin <onboarding@resend.dev>',
        'to' => [$validSandboxEmail],
        'subject' => 'Reporte Diario de Citas (' . $today . ')',
        'html' => $htmlAdmin
    ]);

    // Send individual email to each doctor with their daily patients
    $appointmentsByDoctor = $appointments->groupBy('doctor_id');
    foreach ($appointmentsByDoctor as $doctorId => $doctorAppointments) {
        $doctor = $doctorAppointments->first()->doctor;
        if ($doctor && $doctor->user) {
            $htmlDoctor = (new DailyDoctorReport($doctorAppointments, $doctor))->render();
            \Illuminate\Support\Facades\Http::withToken($apiKey)->withoutVerifying()->post('https://api.resend.com/emails', [
                'from' => 'Pedrini Sistema <onboarding@resend.dev>',
                'to' => [$validSandboxEmail], // Solo Sandbox para evitar bloqueo de Resend
                'subject' => 'Tus Citas Programadas (' . $today . ')',
                'html' => $htmlDoctor
            ]);
        }
    }
    
})->dailyAt('08:00')->name('send_daily_admin_report');
