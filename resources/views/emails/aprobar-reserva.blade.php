<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reserva Aprobada</title>
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
            <h2>¡Reserva Aprobada!</h2>
        </div>
        <div class="content">
            <p>Hola, <strong>{{ $reserva->usuario->USU_PRIMER_NOMBRE ?? 'Usuario' }}</strong>:</p>
            <p>Te informamos que tu solicitud de reserva en el sistema <strong>SIGER</strong> ha sido <strong>aprobada</strong> exitosamente.</p>
            
            <div class="details">
                <p style="margin: 5px 0;"><strong>ID de Reserva:</strong> #{{ $reserva->res_id }}</p>
                <p style="margin: 5px 0;"><strong>Motivo:</strong> {{ $reserva->res_motivo }}</p>
                <p style="margin: 5px 0;"><strong>Estado:</strong> Aprobada</p>
                
                <!-- SECCIÓN DE RECURSOS ASIGNADOS (ACTIVOS Y AULAS) -->
                <p style="margin: 12px 0 5px 0;"><strong>Recursos Asignados:</strong></p>
                <ul style="margin: 0; padding-left: 20px; color: #555;">
                    @forelse($reserva->detalles as $detalle)
                        <li style="margin-bottom: 4px;">
                            @if($detalle->act_id && $detalle->activo)
                                {{-- Es un Activo (con o sin aula destino) --}}
                                💻 <strong>{{ $detalle->activo->act_nombre }}</strong>
                                @if($detalle->aulaDestino)
                                    <span style="color: #444;">(Destino: {{ $detalle->aulaDestino->aula_nombre }})</span>
                                @endif
                            @elseif($detalle->aula_id && $detalle->aula)
                                {{-- Es un Aula Independiente --}}
                                🏫 <strong>Aula: {{ $detalle->aula->aula_nombre }}</strong>
                            @else
                                Recurso (ID: N/A)
                            @endif

                            @if(!empty($detalle->det_re_fecha_ini))
                                <span style="font-size: 12px; color: #777;">(Desde: {{ $detalle->det_re_fecha_ini }})</span>
                            @endif
                        </li>
                    @empty
                        <li>No hay detalles de recursos registrados.</li>
                    @endforelse
                </ul>
            </div>

            <p>Puedes ingresar a la plataforma para consultar más detalles sobre tus recursos asignados.</p>
        </div>
        <div class="footer">
            <p>Este es un mensaje automático enviado por SIGER. Por favor no respondas a este correo.</p>
        </div>
    </div>
</body>
</html>