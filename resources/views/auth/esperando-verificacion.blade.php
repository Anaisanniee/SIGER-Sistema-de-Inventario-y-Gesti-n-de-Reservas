<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verifica tu dispositivo - SIGER</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6f9; text-align: center; padding: 50px;">
    <div style="max-width: 500px; background: #ffffff; margin: 0 auto; padding: 40px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="color: #e3342f;">🔒 Verificación de Dispositivo Requerida</h2>
        <p>Hemos enviado un enlace de confirmación a tu correo electrónico.</p>
        <p>Haz clic en el botón <strong>"Sí, soy yo"</strong> en tu correo. Esta pantalla se actualizará automáticamente en cuanto lo hagas.</p>
        
        <div style="background: #f8fafc; padding: 15px; border-radius: 5px; margin: 20px 0; border: 1px dashed #cbd5e0;">
            <p style="margin: 0; font-size: 14px; color: #555;" id="estado-texto">⏳ Esperando confirmación...</p>
        </div>

        <a href="{{ route('login') }}" style="background-color: #6c757d; color: #ffffff; padding: 8px 15px; text-decoration: none; border-radius: 5px; display: inline-block; font-size: 13px; margin-top: 10px;">Cancelar e ir al Login</a>
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