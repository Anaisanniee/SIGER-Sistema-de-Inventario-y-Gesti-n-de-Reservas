<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva Rechazada - SIGER</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f9; font-family: 'Segoe UI', Arial, sans-serif; color: #333333;">

    <!-- Contenedor general para centrar en clientes de correo -->
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f4f6f9; padding: 30px 0;">
        <tr>
            <td align="center">
                
                <!-- Tarjeta Principal (Borde superior en rojo) -->
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" style="background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.06); border-top: 5px solid #dc3545;">
                    
                    <!-- Encabezado -->
                    <tr>
                        <td style="padding: 30px 40px 20px 40px; text-align: center; border-bottom: 1px solid #edf2f7;">
                            <h1 style="color: #dc3545; margin: 0; font-size: 22px; font-weight: 700; letter-spacing: 0.5px;">
                                Solicitud de Reserva Rechazada
                            </h1>
                            <p style="color: #6c757d; margin: 5px 0 0 0; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">
                                Sistema SIGER
                            </p>
                        </td>
                    </tr>

                    <!-- Contenido -->
                    <tr>
                        <td style="padding: 30px 40px; font-size: 15px; color: #333333; line-height: 1.6;">
                            <p style="margin-top: 0;">Hola, <strong>{{ $reserva->usuario->USU_PRIMER_NOMBRE ?? 'Usuario' }}</strong>:</p>
                            
                            <p>Lamentamos informarte que tu solicitud de reserva en el sistema <strong>SIGER</strong> ha sido <strong style="color: #dc3545;">rechazada</strong>.</p>
                            
                            <!-- Caja de Detalles -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background: #f8f9fa; border-radius: 6px; margin: 25px 0; border-left: 4px solid #dc3545;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 8px 0; font-size: 14px; color: #495057;">
                                            <strong>ID de Reserva:</strong> #{{ $reserva->res_id }}
                                        </p>
                                        <p style="margin: 0 0 8px 0; font-size: 14px; color: #495057;">
                                            <strong>Motivo solicitado:</strong> {{ $reserva->res_motivo }}
                                        </p>
                                        <p style="margin: 0 0 15px 0; font-size: 14px; color: #495057;">
                                            <strong>Estado:</strong> <span style="color: #dc3545; font-weight: 600;">Rechazada</span>
                                        </p>
                                        
                                        <div style="border-top: 1px solid #e2e8f0; padding-top: 12px; margin-top: 12px;">
                                            <p style="margin: 0 0 8px 0; font-size: 14px; font-weight: 600; color: #2c3e50;">
                                                Recursos que habías solicitado:
                                            </p>
                                            <ul style="margin: 0; padding-left: 18px; color: #555555; font-size: 14px;">
                                                @forelse($reserva->detalles as $detalle)
                                                    <li style="margin-bottom: 6px; line-height: 1.4;">
                                                        @if($detalle->act_id && $detalle->activo)
                                                            💻 <strong>{{ $detalle->activo->act_nombre }}</strong>
                                                            @if($detalle->aulaDestino)
                                                                <span style="color: #6c757d;">(Destino: {{ $detalle->aulaDestino->aula_nombre }})</span>
                                                            @endif
                                                        @elseif($detalle->aula_id && $detalle->aula)
                                                            🏫 <strong>Aula: {{ $detalle->aula->aula_nombre }}</strong>
                                                        @else
                                                            Recurso (ID: N/A)
                                                        @endif

                                                        @if(!empty($detalle->det_re_fecha_ini))
                                                            <span style="font-size: 12px; color: #888888; display: block;">Desde: {{ $detalle->det_re_fecha_ini }}</span>
                                                        @endif
                                                    </li>
                                                @empty
                                                    <li>No hay detalles de recursos registrados.</li>
                                                @endforelse
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- Botón de Acción -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 25px 0 15px 0; text-align: center;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/login') }}" target="_blank" style="background-color: #dc3545; color: #ffffff; padding: 12px 25px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-block; box-shadow: 0 2px 5px rgba(220, 53, 69, 0.2);">
                                            Ingresar a la Plataforma
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin-bottom: 0; color: #555555; font-size: 14px;">
                                Si tienes dudas sobre los motivos del rechazo, puedes comunicarte con el administrador o realizar una nueva solicitud ajustando los horarios o recursos.
                            </p>
                        </td>
                    </tr>

                    <!-- Pie de Página -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 20px 40px; text-align: center; border-top: 1px solid #edf2f7;">
                            <p style="margin: 0; font-size: 12px; color: #adb5bd; line-height: 1.4;">
                                Este es un mensaje automático enviado por <strong>SIGER</strong>. Por favor no respondas a este correo.
                            </p>
                        </td>
                    </tr>

                </table>
                
            </td>
        </tr>
    </table>

</body>
</html>