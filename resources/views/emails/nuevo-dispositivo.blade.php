<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Alerta de Seguridad - SIGER</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 20px; color: #333;">
    <div style="max-width: 600px; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="color: #e3342f; text-align: center;">⚠️ Nuevo inicio de sesión detectado</h2>
        
        <p>Hola, <strong>{{ $user->USU_NOMBRES ?? $user->name }}</strong>,</p>
        <p>Hemos detectado un acceso a tu cuenta en <strong>SIGER</strong> desde un dispositivo o navegador desconocido.</p>
        
        <div style="background: #f8fafc; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #3490dc;">
            <p style="margin: 5px 0;"><strong>Dirección IP:</strong> {{ $ip }}</p>
            <p style="margin: 5px 0;"><strong>Fecha y Hora:</strong> {{ now() }}</p>
        </div>

        <p>Si fuiste tú, haz clic en el siguiente botón para registrar este equipo como seguro y permitir tus próximos accesos:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $urlAutorizacion }}" style="background-color: #3490dc; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">Sí, soy yo (Confiar en este equipo)</a>
        </div>

        <p style="font-size: 13px; color: #666;">Si <strong>no reconoces</strong> esta actividad, ignora este mensaje y cambia tu contraseña de inmediato.</p>
        
        <p style="margin-top: 30px; font-size: 12px; color: #888; text-align: center;">
            Sistema de Gestión de Reservas (SIGER) - Mensaje automático.
        </p>
    </div>
</body>
</html>