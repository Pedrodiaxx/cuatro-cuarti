<?php

namespace App\Mail;

use App\Models\Appointment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    /**
     * Create a new message instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Comprobante de Cita Médica',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment.created',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView('pdf.appointment_receipt', ['appointment' => $this->appointment]);

        // ---- OPCIÓN 2: GUARDAR TEMPORALMENTE EN LA CARPETA PÚBLICA PARA VERLO ----
        if (!file_exists(public_path('pdfs'))) {
            mkdir(public_path('pdfs'), 0777, true);
        }
        file_put_contents(public_path('pdfs/comprobante_cita_' . $this->appointment->id . '.pdf'), $pdf->output());
        // --------------------------------------------------------------------------

        return [
            Attachment::fromData(fn () => $pdf->output(), 'comprobante_cita.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
