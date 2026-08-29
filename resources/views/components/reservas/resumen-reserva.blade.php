{{-- resources/views/components/reservas/resumen-reserva.blade.php --}}
@props([
    'reserva' => null, 
    'mostrarSubtitulo' => true
])

@php
    $reservaValida = $reserva ?? new \stdClass();

    $usuario = $reservaValida->usuario ?? null;
    $nombreSolicitante = $usuario->nombres ?? ($usuario->name ?? 'Docente Solicitante');
    $identificacionUsuario = $usuario->identificacion ?? ($usuario->cedula ?? 'N/A');
    $emailUsuario = $usuario->email ?? 'correo@colegio.edu.co';

    $fechaInicio = !empty($reservaValida->res_fecha_inicio) ? \Carbon\Carbon::parse($reservaValida->res_fecha_inicio)->format('Y-m-d') : 'N/A';
    $horaInicio = !empty($reservaValida->res_fecha_inicio) ? \Carbon\Carbon::parse($reservaValida->res_fecha_inicio)->format('h:i A') : 'N/A';
    
    $fechaFin = !empty($reservaValida->res_fecha_fin) ? \Carbon\Carbon::parse($reservaValida->res_fecha_fin)->format('Y-m-d') : $fechaInicio;
    $horaFin = !empty($reservaValida->res_fecha_fin) ? \Carbon\Carbon::parse($reservaValida->res_fecha_fin)->format('h:i A') : 'N/A';

    $motivoReserva = $reservaValida->res_motivo ?? ($reservaValida->motivo ?? 'Desarrollo de clase práctica y actividades pedagógicas programadas.');

    $detalles = isset($reservaValida->detalles) ? $reservaValida->detalles : collect();
    $esMultiple = $detalles->count() > 1;
    
    $primerDetalle = $detalles->first() ?? null;
    $aulaUso = 'N/A';
    if ($primerDetalle) {
        if (isset($primerDetalle->aula) && $primerDetalle->aula) {
            $aulaUso = $primerDetalle->aula->aula_nombre;
        } elseif (!empty($primerDetalle->det_re_aula_destino_act)) {
            $aulaUso = $primerDetalle->det_re_aula_destino_act;
        }
    }

    $listaRecursos = $detalles->map(function($det) {
        return (object)[
            'tipo'   => !empty($det->act_id) ? 'activo' : 'aula',
            'nombre' => $det->activo->act_nombre ?? ($det->aula->aula_nombre ?? 'Recurso General'),
            'serial' => $det->activo->act_serial ?? 'Sin Serial',
            'marca'  => $det->activo->act_marca ?? 'N/A'
        ];
    })->toArray();
@endphp

<div class="tarjeta-reserva-siger tarjeta-confirmacion-paso3">
    <div class="encabezado-resumen-final">
        <h2><i class="bi bi-file-earmark-check-fill text-verde"></i> Resumen de Reserva</h2>
        @if($mostrarSubtitulo)
            <p class="subtitulo-tarjeta">Por favor, verifique todos los datos antes de confirmar la solicitud del recurso.</p>
        @endif
    </div>

    <div class="grid-resumen-bloques">
        {{-- Bloque 1: Datos del Solicitante --}}
        <div class="bloque-resumen-interno">
            <h3><i class="bi bi-person-vcard"></i> Datos del Solicitante</h3>
            <div class="contenido-resumen-item">
                <p><strong>Nombre:</strong> <span id="resumen-solicitante">{{ $nombreSolicitante }}</span></p>
                <p><strong>Identificación:</strong> <span id="resumen-identificacion">{{ $identificacionUsuario }}</span></p>
                <p><strong>Correo Electrónico:</strong> <span id="resumen-email">{{ $emailUsuario }}</span></p>
            </div>
        </div>

        {{-- Bloque 2: COMPONENTE DETALLE RECURSO --}}
        <div class="bloque-resumen-interno" id="resumen-bloque-recurso">
            <h3>
                @if($esMultiple)
                    <i class="bi bi-boxes"></i> Recursos Seleccionados ({{ $detalles->count() }})
                @elseif($primerDetalle && $primerDetalle->aula)
                    <i class="bi bi-door-open"></i> Datos del Salón
                @else
                    <i class="bi bi-laptop"></i> Datos del Recurso
                @endif
            </h3>

            <x-reservas.detalle-recurso 
                :tipoRecurso="$listaRecursos[0]->tipo ?? 'activo'"
                :recursoNombre="$listaRecursos[0]->nombre ?? 'Recurso'"
                :serial="$listaRecursos[0]->serial ?? 'Sin Serial'"
                :marca="$listaRecursos[0]->marca ?? 'N/A'"
                :activos="$listaRecursos" 
            />
        </div>

        {{-- Bloque: Motivo de la Reserva --}}
        <div class="bloque-resumen-interno grid-ancho-completo">
            <h3><i class="bi bi-chat-left-text-fill"></i> Motivo de la Solicitud</h3>
            <div class="contenido-resumen-item">
                <p class="motivo-texto-siger">
                    <span id="resumen-motivo">{{ $motivoReserva }}</span>
                </p>
            </div>
        </div>

        {{-- Bloque 3: Fecha y Horario --}}
        <div class="bloque-resumen-interno grid-ancho-completo">
            <h3><i class="bi bi-calendar3"></i> Asignación de Tiempos</h3>
            <div class="grid-tiempos-paso3">
                <div class="tiempo-caja">
                    <span class="tiempo-titulo">Inicio de Reserva</span>
                    <p><i class="bi bi-calendar-event"></i> <strong>Fecha:</strong> <span id="resumen-fecha-inicio">{{ $fechaInicio }}</span></p>
                    <p><i class="bi bi-clock"></i> <strong>Hora:</strong> <span id="resumen-hora-inicio">{{ $horaInicio }}</span></p>
                </div>
                <div class="tiempo-caja">
                    <span class="tiempo-titulo">Finalización de Reserva</span>
                    <p><i class="bi bi-calendar-check"></i> <strong>Fecha:</strong> <span id="resumen-fecha-fin">{{ $fechaFin }}</span></p>
                    <p><i class="bi bi-clock"></i> <strong>Hora:</strong> <span id="resumen-hora-fin">{{ $horaFin }}</span></p>
                </div>
            </div>
        </div>

        {{-- Bloque 4: Destino del Traslado --}}
        @if($aulaUso !== 'N/A')
            <div class="bloque-resumen-interno grid-ancho-completo bloque-ubicacion-paso3" id="resumen-bloque-ubicacion">
                <h3><i class="bi bi-geo-alt-fill"></i> Ubicación de Destino Asignada</h3>
                <div class="contenido-resumen-item">
                    <p>El recurso será trasladado pedagógicamente para su uso en el siguiente espacio del colegio:</p>
                    <p style="margin-top: 0.75rem;">
                        <strong>Lugar de uso:</strong> 
                        <span class="badge-aula-uso">
                            <i class="bi bi-pin-map-fill"></i> <span id="resumen-aula-uso">{{ $aulaUso }}</span>
                        </span>
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>