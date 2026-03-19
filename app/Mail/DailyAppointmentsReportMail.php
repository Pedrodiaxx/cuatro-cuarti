<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class DailyAppointmentsReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointments;
    public $recipientName;

    /**
     * Create a new message instance.
     */
    public function __construct(Collection $appointments, $recipientName = 'Administrador')
    {
        $this->appointments = $appointments;
        $this->recipientName = $recipientName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reporte Diario de Citas - ' . now()->format('d/m/Y'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment.daily_report',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView('pdf.daily_report', ['appointments' => $this->appointments]);

        if (!file_exists(public_path('pdfs'))) {
            mkdir(public_path('pdfs'), 0777, true);
        }
        file_put_contents(public_path('pdfs/reporte_citas_prueba.pdf'), $pdf->output());

        return [
            Attachment::fromData(fn () => $pdf->output(), 'reporte_citas_' . now()->format('Y_m_d') . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
