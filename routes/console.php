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

    Mail::to('admin@example.com')->send(new DailyAppointmentsReportMail($appointments));
})->dailyAt('08:00');
