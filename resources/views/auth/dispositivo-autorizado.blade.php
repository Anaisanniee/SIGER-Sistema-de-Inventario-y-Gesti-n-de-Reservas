<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispositivo Autorizado - SIGER</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; text-align: center; margin: 0; padding: 0; display: flex; align-items: center; justify-content: center; height: 100vh;">

    <div style="max-width: 500px; width: 100%; background: #ffffff; margin: 0 auto; padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">
        
        <!-- Icono de éxito elegante -->
        <div style="margin-bottom: 20px;">
            <div style="display: inline-flex; align-items: center; justify-content: center; background-color: rgba(40, 167, 69, 0.1); width: 75px; height: 75px; border-radius: 50%;">
                <span style="color: #28a745; font-size: 38px;">✓</span>
            </div>
        </div>

        <h2 style="color: #28a745; margin-top: 0; margin-bottom: 15px; font-size: 24px;">¡Dispositivo Autorizado con Éxito!</h2>
        
        <p style="color: #495057; font-size: 15px; line-height: 1.6; margin-bottom: 15px;">
            Has confirmado que eres tú[cite: 9]. Este equipo ha quedado registrado como seguro en <strong style="color: #2c3e50;">SIGER</strong>[cite: 9].
        </p>
        
        <p style="color: #6c757d; font-size: 14px; line-height: 1.5; margin-bottom: 25px;">
            Ya puedes cerrar esta pestaña y volver a iniciar sesión normalmente[cite: 9].
        </p>

        <a href="{{ route('login') }}" style="background-color: #28a745; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 6px; display: inline-block; font-size: 14px; font-weight: 600; box-shadow: 0 4px 10px rgba(40, 167, 69, 0.2);">
            Ir al Inicio de Sesión
        </a>
        
    </div>

    <!-- Script para intentar cerrar esta pestaña del correo automáticamente -->
    <script>
        setTimeout(function() {
            window.close();
        }, 2000); // Intenta cerrarse sola a los 2 segundos[cite: 9]
    </script>
    
</body>
</html>