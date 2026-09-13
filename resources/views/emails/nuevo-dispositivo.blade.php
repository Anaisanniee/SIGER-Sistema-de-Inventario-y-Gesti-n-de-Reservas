<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Alerta de Seguridad - SIGER</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; }
        .container { max-width: 600px; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .header { text-align: center; border-bottom: 2px solid #28a745; padding-bottom: 15px; margin-bottom: 20px; }
        .header h2 { color: #28a745; margin: 0; }
        .content { font-size: 16px; color: #333333; line-height: 1.5; }
        .details { background: #f8f9fa; padding: 15px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #28a745; }
        .footer { text-align: center; font-size: 12px; color: #888888; margin-top: 30px; border-top: 1px solid #eee; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Verificación de Dispositivo</h2>
        </div>
        <div class="content">
        
        <p>Hola, <strong>{{ $user->USU_NOMBRES ?? $user->name }}</strong>,</p>
        <p>Hemos detectado un acceso a tu cuenta en <strong>SIGER</strong> desde un dispositivo o navegador desconocido.</p>
        
        <div class="details">
            <p><strong>Dirección IP:</strong> {{ $ip }}</p>
            <p><strong>Fecha y Hora:</strong> {{ now() }}</p>
        </div>

        <p>Si fuiste tú, haz clic en el siguiente botón para registrar este equipo como seguro y permitir tus próximos accesos:</p>
        
        <p style="text-align: center; margin: 30px 0;">
            <a href="{{ $urlautorizacion }}" style="background-color: #28a745; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">Sí, soy yo (Confiar en este dispositivo)</a>
        </p>

        <p style="font-size: 13px; color: #666;">Si <strong>no reconoces</strong> esta actividad, ignora este mensaje y cambia tu contraseña de inmediato.</p>
        
        <p style="margin-top: 30px; font-size: 12px; color: #888; text-align: center;">
            Sistema de Gestión de Reservas (SIGER) - Mensaje automático.
        </p>
        </div>
    </div>
</body>
</html>