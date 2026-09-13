<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificación de Dispositivo - SIGER</title>
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
        <p>Hemos enviado un enlace de confirmación a tu correo electrónico.</p>
        <p>Haz clic en el botón <strong>"Sí, soy yo"</strong> en tu correo. Esta pantalla se actualizará automáticamente en cuanto lo hagas.</p>
        
        <div style="background: #f8fafc; padding: 15px; border-radius: 5px; margin: 20px 0; border: 1px dashed #cbd5e0;">
            <p style="margin: 0; font-size: 14px; color: #555;" id="estado-texto"> Esperando confirmación...</p>
        </div>

        <a href="{{ route('login') }}" style="background-color: #228827; color: #ffffff; padding: 8px 15px; text-decoration: none; border-radius: 5px; display: inline-block; font-size: 13px; margin-top: 10px;">Cancelar e ir al Login</a>
        
         
        <div class="footer">
            <p>Si no recibes el correo, revisa tu carpeta de spam o solicita un nuevo enlace.</p>
            <p style="font-size: 12px; color: #888;">Sistema de Gestión de Reservas (SIGER) - Mensaje automático.</p>
        </div>

    </div>

<!-- Script de auto-verificación en segundo plano -->
    <script>
        setInterval(function() {
            fetch("{{ route('device.check.status') }}")
                .then(response => response.json())
                .then(data => {
                    if (data.autorizado) {
                        document.getElementById('estado-texto').innerText = "¡Confirmado! Entrando al sistema...";
                        // La misma pestaña de espera se convierte en el dashboard del usuario
                        window.location.href = data.redirect;
                    }
                })
                .catch(error => console.error('Error:', error));
        }, 3000); // Revisa cada 3 segundos
    </script>
</body>
</html>