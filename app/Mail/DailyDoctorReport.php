<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyDoctorReport extends Mailable
{
    use Queueable, SerializesModels;

    public $appointments;
    public $doctor;

    public function __construct($appointments, $doctor)
    {
        $this->appointments = $appointments;
        $this->doctor = $doctor;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tus Citas Programadas para Hoy - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.daily-doctor-report',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
