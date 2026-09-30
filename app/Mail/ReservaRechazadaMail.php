<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservaRechazadaMail extends Mailable
{
    use Queueable, SerializesModels;

    // Declaramos la variable pública para que esté disponible en la vista del correo
    public $reserva;

    /**
     * Create a new message instance.
     */
    public function __construct($reserva)
    {
        $this->reserva = $reserva;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ Solicitud de Reserva Rechazada - SIGER',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            // Apuntamos a la vista Blade que crearemos para el correo (por ejemplo: resources/views/emails/reserva_rechazada.blade.php)
            view: 'emails.reserva_rechazada',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}