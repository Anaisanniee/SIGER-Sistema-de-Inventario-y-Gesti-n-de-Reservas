<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dispositivo Autorizado - SIGER</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6f9; text-align: center; padding: 50px;">
    <div style="max-width: 500px; background: #ffffff; margin: 0 auto; padding: 40px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="color: #38c172;">¡Dispositivo Autorizado con Éxito!</h2>
        <p>Has confirmado que eres tú. Este equipo ha quedado registrado como seguro en <strong>SIGER</strong>.</p>
        <p>Ya puedes cerrar esta pestaña y volver a iniciar sesión normalmente.</p>
        <a href="{{ route('login') }}" style="background-color: #38c172; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 20px;">Ir al Inicio de Sesión</a>
    </div>

    <!-- Script para intentar cerrar esta pestaña del correo automáticamente -->
    <script>
        setTimeout(function() {
            window.close();
        }, 2000); // Intenta cerrarse sola a los 2 segundos
    </script>
    
</body>
</html>

