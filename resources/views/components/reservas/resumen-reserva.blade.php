{{-- resources/views/components/reservas/resumen-reserva.blade.php --}}
@props([
    'tipoRecurso'      => 'activo',
    'solicitante'      => Auth::user()->nombres ?? 'Docente Solicitante',
    'identificacion'   => Auth::user()->identificacion ?? '1.004.234.XXX',
    'email'            => Auth::user()->email ?? 'docente@colegio.edu.co',
    'recursoNombre'    => 'Laboratorio de Sistemas A',
    'capacidad'        => '35 Estudiantes',
    'serial'           => 'DELL-5420-X92',
    'marca'            => 'Dell Inspiron',
    'fechaInicio'      => '2026-07-10',
    'horaInicio'       => '07:00 AM',
    'fechaFin'         => '2026-07-10',
    'horaFin'          => '09:30 AM',
    'aulaUso'          => 'Salón 601',
    'mostrarSubtitulo' => true,
    'activos'          => [] // Array de recursos si son múltiples
])

<div class="tarjeta-reserva-siger tarjeta-confirmacion-paso3">
        
    <div class="encabezado-resumen-final">
        <h2><i class="bi bi-file-earmark-check-fill text-verde"></i> Resumen Final de la Reserva</h2>
        @if($mostrarSubtitulo)
            <p class="subtitulo-tarjeta">Por favor, verifique todos los datos antes de confirmar la solicitud del recurso.</p>
        @endif
    </div>

    <div class="grid-resumen-bloques">
        
        {{-- Bloque 1: Datos del Solicitante --}}
        <div class="bloque-resumen-interno">
            <h3><i class="bi bi-person-vcard"></i> Datos del Solicitante</h3>
            <div class="contenido-resumen-item">
                <p><strong>Nombre:</strong> <span id="resumen-solicitante">{{ $solicitante }}</span></p>
                <p><strong>Identificación:</strong> <span id="resumen-identificacion">{{ $identificacion }}</span></p>
                <p><strong>Correo Electrónico:</strong> <span id="resumen-email">{{ $email }}</span></p>
            </div>
        </div>

        {{-- Bloque 2: COMPONENTE DETALLE RECURSO (Maneja 1 o Varios dinámicamente) --}}
        <div class="bloque-resumen-interno" id="resumen-bloque-recurso">
            <h3>
                @if(!empty($activos) && count($activos) > 1)
                    <i class="bi bi-boxes"></i> Recursos Seleccionados
                @elseif($tipoRecurso === 'aula')
                    <i class="bi bi-door-open"></i> Datos del Salón
                @else
                    <i class="bi bi-laptop"></i> Datos del Recurso
                @endif
            </h3>

            {{-- Invocamos el componente reutilizable en el resumen --}}
            <x-reservas.detalle-recurso 
                :tipoRecurso="$tipoRecurso"
                :recursoNombre="$recursoNombre"
                :capacidad="$capacidad"
                :serial="$serial"
                :marca="$marca"
                :activos="$activos"
            />
        </div>

        {{-- Bloque: Motivo de la Reserva --}}
        <div class="bloque-resumen-interno grid-ancho-completo">
            <h3><i class="bi bi-chat-left-text-fill"></i> Motivo de la Solicitud</h3>
            <div class="contenido-resumen-item">
                <p class="motivo-texto-siger">
                    <span id="resumen-motivo">
                        {{ $motivo ?? session('res_motivo') ?? 'Desarrollo de clase práctica y actividades pedagógicas programadas.' }}
                    </span>
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

        {{-- Bloque 4: Destino del Traslado (Solo para Activos) --}}
        @if($tipoRecurso !== 'aula')
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