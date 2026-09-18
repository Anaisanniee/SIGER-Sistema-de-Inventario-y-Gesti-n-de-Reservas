<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Dispositivo - SIGER</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; margin: 0; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .card-siger { max-width: 520px; width: 100%; background: #ffffff; padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); text-align: center; border: none; }
    </style>
</head>
<body>

    <div class="card-siger">
        <!-- Icono de Seguridad -->
        <div class="mb-3">
            <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-circle" style="width: 75px; height: 75px;">
                <i class="fas fa-shield-halved text-success fa-2x"></i>
            </div>
        </div>

        <h3 class="fw-bold text-dark mb-3">Verificación de Dispositivo</h3>
        <hr class="w-25 mx-auto text-success mb-4" style="height: 3px; opacity: 1;">

        <p class="text-secondary" style="font-size: 0.95rem; line-height: 1.5;">Hemos enviado un enlace de confirmación a tu correo electrónico.</p>
        <p class="text-secondary mb-4" style="font-size: 0.90rem; line-height: 1.5;">Haz clic en el botón <strong>"Sí, soy yo"</strong> en tu correo. Esta pantalla se actualizará automáticamente en cuanto lo hagas.</p>
        
        <!-- Caja de Estado con Spinner -->
        <div style="background: #f8fafc; padding: 15px; border-radius: 8px; margin: 20px 0; border: 1px dashed #cbd5e0;" class="d-flex align-items-center justify-content-center gap-2">
            <div class="spinner-border spinner-border-sm text-success" role="status"></div>
            <span style="font-size: 14px; color: #495057; font-weight: 500;" id="estado-texto">Esperando confirmación...</span>
        </div>

        <div class="d-grid mt-3">
            <a href="{{ route('login') }}" class="btn btn-outline-secondary rounded-pill py-2 text-decoration-none fw-semibold" style="font-size: 14px;">
                <i class="fas fa-arrow-left me-1"></i> Cancelar e ir al Login
            </a>
        </div>
         
        <div class="mt-4 pt-3 border-top" style="font-size: 12px; color: #888888; line-height: 1.4;">
            <p class="mb-1">Si no recibes el correo, revisa tu carpeta de spam o solicita un nuevo enlace.</p>
            <p class="mb-0">Sistema de Gestión de Reservas (SIGER) - Mensaje automático.</p>
        </div>
    </div>

    <!-- Script de auto-verificación en segundo plano (Conservado intacto) -->
    <script>
        setInterval(function() {
            fetch("{{ route('device.check.status') }}")
                .then(response => response.json())
                .then(data => {
                    if (data.autorizado) {
                        document.getElementById('estado-texto').innerText = "¡Confirmado! Entrando al sistema...";
                        window.location.href = data.redirect;
                    }
                })
                .catch(error => console.error('Error:', error));
        }, 3000); 
    </script>
</body>
</html>