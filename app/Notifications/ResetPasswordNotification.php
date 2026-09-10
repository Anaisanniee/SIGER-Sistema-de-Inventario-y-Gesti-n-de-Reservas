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

        // 1. Obtener minutos de expiración desde la configuración
        $expireMinutes = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire', 60);

        // 2. Extraer nombre del usuario para el saludo personalizado
        $nombreUsuario = trim(($notifiable->USU_PRIMER_NOMBRE ?? '') . ' ' . ($notifiable->USU_PRIMER_APELLIDO ?? ''));
        if (empty($nombreUsuario)) {
            $nombreUsuario = $notifiable->USU_NOMBRES ?? $notifiable->name ?? 'Usuario';
        }

        return (new MailMessage)
            ->subject('Notificación de restablecimiento de contraseña - SIGER')
            ->view('vendor.notifications.email', [
                'actionUrl'  => $url,
                'actionText' => 'Restablecer contraseña',
                'greeting'   => '¡Hola, ' . $nombreUsuario . '!',
                'introLines' => [
                    'Recibiste este correo porque se solicitó un restablecimiento de contraseña para tu cuenta en la plataforma SIGER.'
                ],
                'outroLines' => [
                    "Este enlace caducará en {$expireMinutes} minutos.",
                    'Si no realizaste esta solicitud, no se requiere ninguna otra acción.'
                ]
            ]);
    }
}