<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Mail;
use App\Models\Appointment;
use App\Mail\DailyAdminReport;
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

    // Send email to admin
    $adminEmail = env('ADMIN_EMAIL', 'admin@pedriniclinica.com');
    Mail::to($adminEmail)->send(new DailyAdminReport($appointments));
    
})->dailyAt('08:00')->name('send_daily_admin_report');
