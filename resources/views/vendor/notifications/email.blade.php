<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Notificación de SIGER' }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; -webkit-font-smoothing: antialiased;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f3f4f6; padding: 30px 10px;">
        <tr>
            <td align="center">
                <!-- Tarjeta Principal -->
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
                    
                    <!-- Encabezado / Header -->
                    <tr>
                        <td align="center" style="background-color: #1e3a8a; padding: 25px 20px;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;">
                                SIGER
                            </h1>
                            <p style="color: #93c5fd; margin: 5px 0 0 0; font-size: 13px;">
                                Sistema de Gestión de Reservas
                            </p>
                        </td>
                    </tr>

                    <!-- Cuerpo del Correo -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <!-- Saludo -->
                            <h2 style="color: #1f2937; font-size: 20px; font-weight: 600; margin-top: 0; margin-bottom: 20px;">
                                {{ $greeting ?? '¡Hola!' }}
                            </h2>

                            <!-- Lineas de Introducción -->
                            @if(isset($introLines))
                                @foreach ($introLines as $line)
                                    <p style="color: #4b5563; font-size: 15px; line-height: 1.6; margin-bottom: 15px;">
                                        {{ $line }}
                                    </p>
                                @endforeach
                            @endif

                            <!-- Botón de Acción -->
                            @if (isset($actionText) && isset($actionUrl))
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 30px 0;">
                                    <tr>
                                        <td align="center">
                                            <a href="{{ $actionUrl }}" target="_blank" style="background-color: #2563eb; color: #ffffff; padding: 14px 28px; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 15px; display: inline-block; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);">
                                                {{ $actionText }}
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            <!-- Lineas de Cierre -->
                            @if(isset($outroLines))
                                @foreach ($outroLines as $line)
                                    <p style="color: #4b5563; font-size: 15px; line-height: 1.6; margin-bottom: 15px;">
                                        {{ $line }}
                                    </p>
                                @endforeach
                            @endif

                            <!-- Despedida -->
                            <p style="color: #4b5563; font-size: 15px; line-height: 1.6; margin-top: 25px; margin-bottom: 0;">
                                Saludos,<br>
                                <strong>El equipo de SIGER</strong>
                            </p>

                            <!-- Subcopy / Enlace alternativo -->
                            @if (isset($actionText) && isset($actionUrl))
                                <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 30px 0 20px 0;">
                                <p style="color: #6b7280; font-size: 12px; line-height: 1.5; margin: 0;">
                                    Si tienes problemas para hacer clic en el botón "{{ $actionText }}", copia y pega la siguiente URL en tu navegador web:
                                </p>
                                <p style="font-size: 12px; line-height: 1.5; margin-top: 8px; word-break: break-all;">
                                    <a href="{{ $actionUrl }}" style="color: #2563eb; text-decoration: underline;">{{ $actionUrl }}</a>
                                </p>
                            @endif
                        </td>
                    </tr>

                    <!-- Pie de Página / Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 20px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="color: #9ca3af; font-size: 12px; margin: 0;">
                                &copy; {{ date('Y') }} SIGER. Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>