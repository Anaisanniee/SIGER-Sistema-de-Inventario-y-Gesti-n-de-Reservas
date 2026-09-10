<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer contraseña - SIGER</title>
    <style>
        /* Estilos generales del correo */
        body {
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        table {
            border-spacing: 0;
            border-collapse: collapse;
        }
        td {
            padding: 0;
        }
        
        /* Contenedor principal */
        .email-wrapper {
            width: 100%;
            background-color: #f3f4f6;
            padding: 40px 15px;
        }
        .email-card {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }

        /* Encabezado (Verde institucional SIGER) */
        .email-header {
            background-color: #065f46;
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .email-header p {
            color: #a7f3d0;
            margin: 6px 0 0 0;
            font-size: 13px;
        }

        /* Cuerpo del contenido */
        .email-body {
            padding: 40px 30px;
            color: #374151;
        }
        .email-body h2 {
            color: #1f2937;
            font-size: 20px;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 20px;
        }
        .email-body p {
            color: #4b5563;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        /* Caja de alerta / cronómetro */
        .timer-box {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 8px;
            padding: 12px 16px;
            margin: 20px 0;
            display: flex;
            align-items: center;
            color: #065f46;
            font-size: 14px;
            font-weight: 500;
        }

        /* Botón de acción principal (Verde vibrante) */
        .btn-container {
            text-align: center;
            margin: 30px 0;
        }
        .btn-primary {
            background-color: #059669;
            color: #ffffff !important;
            padding: 14px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            display: inline-block;
            box-shadow: 0 4px 6px rgba(5, 150, 105, 0.2);
            transition: background-color 0.2s;
        }
        .btn-primary:hover {
            background-color: #047857;
        }

        /* Línea divisoria y enlace alternativo */
        .email-divider {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 30px 0 20px 0;
        }
        .email-subcopy {
            color: #6b7280;
            font-size: 12px;
            line-height: 1.5;
            margin: 0 0 8px 0;
        }
        .email-link-alt {
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
            word-break: break-all;
        }
        .email-link-alt a {
            color: #059669;
            text-decoration: underline;
        }

        /* Pie de página */
        .email-footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .email-footer p {
            color: #9ca3af;
            font-size: 12px;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-card">
            
            <!-- Encabezado -->
            <div class="email-header">
                <h1>SIGER</h1>
                <p>Sistema de Inventario y Gestión de Reservas</p>
            </div>

            <!-- Cuerpo -->
            <div class="email-body">
                <h2>{{ $greeting }}</h2>

                @foreach ($introLines as $line)
                    <p>{{ $line }}</p>
                @endforeach

                <!-- Cronómetro / Aviso de tiempo límite -->
                <div class="timer-box">
                    <span>⏱ Este enlace de recuperación expirará en <strong>30 minutos</strong> por seguridad.</span>
                </div>

                @if (!empty($actionUrl))
                    <div class="btn-container">
                        <a href="{{ $actionUrl }}" class="btn-primary">
                            {{ $actionText ?? 'Restablecer contraseña' }}
                        </a>
                    </div>
                @endif

                @foreach ($outroLines as $line)
                    <p style="color: #64748b; font-size: 0.9em;">{{ $line }}</p>
                @endforeach

                <!-- Enlace alternativo de respaldo -->
                @if (!empty($actionUrl))
                    <hr class="email-divider">
                    <p class="email-subcopy">Si tienes problemas para hacer clic en el botón, copia y pega la siguiente URL en tu navegador web:</p>
                    <p class="email-link-alt"><a href="{{ $actionUrl }}">{{ $actionUrl }}</a></p>
                @endif
            </div>

            <!-- Pie de página -->
            <div class="email-footer">
                <p>&copy; {{ date('Y') }} SIGER. Todos los derechos reservados.</p>
            </div>

        </div>
    </div>
</body>
</html>