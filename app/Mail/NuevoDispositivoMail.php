<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class NuevoDispositivoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $ip;
    public $urlAutorizacion;

    public function __construct($user, $ip, $userAgent)
    {
        $this->user = $user;
        $this->ip = $ip;

        // Generamos una URL firmada que caduca en 60 minutos e incluye los datos del dispositivo
        $this->urlAutorizacion = URL::signedRoute('device.authorize', [
            'user'       => $user->getKey(),
            'ip'         => $ip,
            'user_agent' => $userAgent,
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ Alerta de seguridad: Nuevo inicio de sesión detectado',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.nuevo-dispositivo',
        );
    }
}