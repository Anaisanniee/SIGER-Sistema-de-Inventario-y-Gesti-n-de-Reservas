<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerta de Seguridad - SIGER</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #334155;">

    <!-- Contenedor General -->
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; padding: 45px 0;">
        <tr>
            <td align="center">
                
                <!-- Tarjeta Principal -->
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" style="background: #ffffff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); overflow: hidden; border: 1px solid #e2e8f0;">
                    
                    <!-- Cabecera Minimalista Limpia (Sin bloque gigante verde, con un toque ejecutivo) -->
                    <tr>
                        <td style="padding: 35px 40px 20px 40px; text-align: center;">
                            <span style="display: inline-block; background-color: #d1e7dd; color: #0f5132; font-size: 11px; font-weight: 700; text-transform: uppercase; padding: 6px 14px; border-radius: 20px; letter-spacing: 1.2px; margin-bottom: 12px;">🛡️ Alerta de Seguridad</span>
                            <h1 style="color: #0f172a; margin: 0; font-size: 22px; font-weight: 600;">Verificación de Nuevo Dispositivo</h1>
                            <p style="color: #64748b; margin: 6px 0 0 0; font-size: 13px;">Sistema de Gestión de Reservas — SIGER</p>
                        </td>
                    </tr>

                    <!-- Línea divisoria sutil -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <hr style="border: none; border-top: 1px solid #f1f5f9; margin: 0;">
                        </td>
                    </tr>

                    <!-- Cuerpo del Mensaje -->
                    <tr>
                        <td style="padding: 35px 40px 30px 40px;">
                            
                            <p style="font-size: 15px; color: #475569; line-height: 1.6; margin-top: 0; margin-bottom: 20px;">
                                Hola, <strong style="color: #0f172a;">{{ $user->USU_NOMBRES ?? $user->name }}</strong>:
                            </p>
                            
                            <p style="font-size: 15px; color: #475569; line-height: 1.6; margin-bottom: 25px;">
                                Hemos detectado un inicio de sesión en tu cuenta de <strong style="color: #0f172a;">SIGER</strong> desde un dispositivo o navegador no reconocido.
                            </p>
                            
                            <!-- Tarjeta de Detalles Técnicos -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background: #f8fafc; border-radius: 8px; margin-bottom: 25px; border: 1px solid #e2e8f0; border-left: 4px solid #198754;">
                                <tr>
                                    <td style="padding: 18px 20px;">
                                        <p style="margin: 0 0 8px 0; font-size: 14px; color: #475569;">
                                            <strong style="color: #0f172a; display: inline-block; width: 120px;">Dirección IP:</strong> 
                                            <span style="font-family: monospace; background: #e2e8f0; padding: 2px 6px; border-radius: 4px; color: #0f172a;">{{ $ip }}</span>
                                        </p>
                                        <p style="margin: 0; font-size: 14px; color: #475569;">
                                            <strong style="color: #0f172a; display: inline-block; width: 120px;">Fecha y Hora:</strong> 
                                            <span>{{ now() }}</span>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 15px; color: #475569; line-height: 1.6; margin-bottom: 25px;">
                                Si reconoces esta actividad, haz clic en el siguiente botón para registrar este equipo como seguro y permitir el acceso:
                            </p>
                            
                            <!-- Botón de Acción Principal (Verde Esmeralda Corporativo de SIGER) -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="padding: 10px 0 25px 0;">
                                        <a href="{{ $urlAutorizacion }}" target="_blank" style="background-color: #198754; color: #ffffff; padding: 14px 32px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px; display: inline-block; box-shadow: 0 4px 12px rgba(25, 135, 84, 0.25);">
                                            Sí, soy yo (Confiar en este dispositivo)
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Alerta de Precaución -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background: #fffbeb; border: 1px solid #fef3c7; border-radius: 8px;">
                                <tr>
                                    <td style="padding: 15px 18px;">
                                        <p style="margin: 0; font-size: 13px; color: #92400e; line-height: 1.5;">
                                            ⚠️ Si <strong>no reconoces</strong> esta actividad, ignora este mensaje y te recomendamos cambiar tu contraseña de inmediato.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            
                        </td>
                    </tr>

                    <!-- Pie de Página (Footer) -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 20px 40px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <p style="font-size: 12px; color: #94a3b8; margin: 0; line-height: 1.4;">
                                Este es un mensaje automático de seguridad del <strong>SIGER</strong>.<br>Por favor, no respondas a este correo.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>