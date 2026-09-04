<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Notificación de restablecimiento de contraseña - SIGER')
            ->view('vendor.notifications.email', [
                'actionUrl' => $url,
                'actionText' => 'Restablecer contraseña',
                'greeting' => '¡Hola, ' . ($notifiable->USU_NOMBRES ?? 'Usuario') . '!',
                'introLines' => [
                    'Recibiste este correo porque se solicitó un restablecimiento de contraseña para tu cuenta.'
                ],
                'outroLines' => [
                    'Este enlace caducará en 60 minutos.',
                    'Si no realizaste esta solicitud, no se requiere ninguna otra acción.'
                ]
            ]);
    }
}