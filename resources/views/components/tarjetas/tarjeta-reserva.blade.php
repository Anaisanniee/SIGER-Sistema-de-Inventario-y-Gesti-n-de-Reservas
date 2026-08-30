@props([
    'id', 'nombre', 'estado', 'foto' => null, 'solicitante', 'fecha', 'horaInicio', 'horaFin', 'ubicacion', 'urlGestion',
    'esSecretaria' => false,
    'modoDashboard' => false
])

<!-- Si es secretaria y está en el dashboard, la tarjeta es un enlace index de gestión. Si es docente, es un botón de modal -->
<{!! $esSecretaria && $modoDashboard ? "a href='$urlGestion'" : "button type='button'" !!} 
    class="tarjeta-reserva-siger {{ strtolower($estado) }} {{ $esSecretaria && $modoDashboard ? 'tarjeta-enlace' : '' }}"
    @if(!($esSecretaria && $modoDashboard))
        data-bs-toggle="modal" 
        data-bs-target="#modalgeneral"
        onclick="cargarDatosModal(event.currentTarget, {{ json_encode([
            'titulo' => $nombre,
            'subtitulo' => $estado,
            'docente' => $solicitante,
            'fecha' => $fecha,
            'hora' => "$horaInicio - $horaFin",
            'ubicacion' => $ubicacion
        ]) }})"
    @endif
>
    <div class="tarjeta-contenido">
        
        <!-- Imagen/Icono -->
        <div class="tarjeta-imagen-contenedor">
            @if($foto)
                <img src="{{ $foto }}" alt="{{ $nombre }}" class="recurso-foto-min">
            @else
                <div class="icono-recurso-reemplazo">
                    <i class="fas fa-desktop"></i>
                </div>
            @endif
        </div>

        <!-- Información Detallada -->
        <div class="tarjeta-detalles">
            <div class="tarjeta-fila-superior">
                <h4 class="recurso-titulo">{{ $nombre }}</h4>
                <span class="badge-estado-siger {{ strtolower($estado) }}">
                    ● {{ ucfirst($estado) }}
                </span>
            </div>

            <div class="tarjeta-info-secundaria">
                <!-- Si está en el dashboard, ocultamos el solicitante y la fecha para hacerla más compacta -->
                @if(!$modoDashboard)
                    <p class="info-item"><i class="fas fa-user"></i> <span>{{ $solicitante }}</span></p>
                    <p class="info-item"><i class="fas fa-calendar-day"></i> <span>{{ $fecha }}</span></p>
                @endif
                
                <p class="info-item"><i class="fas fa-clock"></i> <span>{{ $horaInicio }} - {{ $horaFin }}</span></p>
                <p class="info-item"><i class="fas fa-map-marker-alt"></i> <span>{{ $ubicacion }}</span></p>
            </div>
        </div>

        <!-- Pequeño indicador visual de acción si es interactiva -->
        <div class="tarjeta-flecha-indicador">
            <i class="fas {{ $esSecretaria && $modoDashboard ? 'fa-chevron-right' : 'fa-eye' }}"></i>
        </div>
    </div>
</{!! $esSecretaria && $modoDashboard ? 'a' : 'button' !!}>