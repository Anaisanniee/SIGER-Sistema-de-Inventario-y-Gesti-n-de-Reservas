<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AprobarReservaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reserva;

    // Recibimos la reserva al instanciar el correo
    public function __construct($reserva)
    {
        $this->reserva = $reserva;
    }

    // Definimos el asunto del correo
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Tu reserva ha sido aprobada - SIGER!',
        );
    }

    // Definimos qué vista de Blade se va a usar
    public function content(): Content
    {
        return new Content(
            view: 'emails.aprobar-reserva', // Ruta de la vista que crearemos
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
