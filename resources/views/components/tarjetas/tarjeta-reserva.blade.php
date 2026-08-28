@props([
<<<<<<< HEAD
    'id', 
    'nombre', 
    'estado', 
    'foto' => null, 
    'solicitante', 
    'fecha', 
    'horaInicio', 
    'horaFin', 
    'ubicacion', 
    'urlGestion',
    'esSecretaria' => false,
    'modoDashboard' => false,
    'esMultiple' => false,
    'recursos' => [] 
])

@php
    $tituloMostrar = $nombre;
    if ($esMultiple || (is_array($nombre) && count($nombre) > 1)) {
        $conteo = is_array($nombre) ? count($nombre) : (is_array($recursos) ? count($recursos) : 'Varios');
        $tituloMostrar = "Reserva Múltiple ({$conteo} recursos)";
    }
@endphp

<{!! $esSecretaria && $modoDashboard ? "a href='$urlGestion'" : "button type='button'" !!} 
    class="tarjeta-reserva-siger {{ strtolower($estado) }} {{ $esSecretaria && $modoDashboard ? 'tarjeta-enlace' : '' }}"
    @if(!($esSecretaria && $modoDashboard))
        onclick="abrirModalConRetardo(this, {{ json_encode([
            'id' => $id,
            'titulo' => $tituloMostrar,
            'estado' => $estado,
            'docente' => $solicitante,
            'fecha' => $fecha,
            'hora' => "$horaInicio - $horaFin",
            'ubicacion' => $ubicacion,
            'esMultiple' => $esMultiple,
            'recursos' => $recursos
        ]) }}, 2000)"
    @endif
>
    <div class="tarjeta-contenido">
        <div class="tarjeta-imagen-contenedor">
            @if($foto)
                <img src="{{ $foto }}" alt="{{ $tituloMostrar }}" class="recurso-foto-min">
            @else
                <div class="icono-recurso-reemplazo">
                    <i class="fas {{ $esMultiple ? 'fa-layer-group' : 'fa-desktop' }}"></i>
=======
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
>>>>>>> origin/backend-Elias
                </div>
            @endif
        </div>

<<<<<<< HEAD
        <div class="tarjeta-detalles">
            <div class="tarjeta-fila-superior">
                <h4 class="recurso-titulo">{{ $tituloMostrar }}</h4>
=======
        <!-- Información Detallada -->
        <div class="tarjeta-detalles">
            <div class="tarjeta-fila-superior">
                <h4 class="recurso-titulo">{{ $nombre }}</h4>
>>>>>>> origin/backend-Elias
                <span class="badge-estado-siger {{ strtolower($estado) }}">
                    ● {{ ucfirst($estado) }}
                </span>
            </div>

            <div class="tarjeta-info-secundaria">
<<<<<<< HEAD
=======
                <!-- Si está en el dashboard, ocultamos el solicitante y la fecha para hacerla más compacta -->
>>>>>>> origin/backend-Elias
                @if(!$modoDashboard)
                    <p class="info-item"><i class="fas fa-user"></i> <span>{{ $solicitante }}</span></p>
                    <p class="info-item"><i class="fas fa-calendar-day"></i> <span>{{ $fecha }}</span></p>
                @endif
                
                <p class="info-item"><i class="fas fa-clock"></i> <span>{{ $horaInicio }} - {{ $horaFin }}</span></p>
                <p class="info-item"><i class="fas fa-map-marker-alt"></i> <span>{{ $ubicacion }}</span></p>
            </div>
        </div>

<<<<<<< HEAD
=======
        <!-- Pequeño indicador visual de acción si es interactiva -->
>>>>>>> origin/backend-Elias
        <div class="tarjeta-flecha-indicador">
            <i class="fas {{ $esSecretaria && $modoDashboard ? 'fa-chevron-right' : 'fa-eye' }}"></i>
        </div>
    </div>
<<<<<<< HEAD
</{!! $esSecretaria && $modoDashboard ? 'a' : 'button' !!}>

@once
<script>
    function abrirModalConRetardo(elemento, datos, tiempoMs = 2000) {
        elemento.style.pointerEvents = 'none';
        elemento.classList.add('cargando-modal');

        setTimeout(() => {
            // Se asume que cargarDatosModal o procesarAccionesModal recibe la info completa
            if (typeof cargarDatosModal === 'function') {
                cargarDatosModal(datos);
            }

            const modalElemento = document.getElementById('modalgeneral');
            if (modalElemento) {
                const modalInstance = bootstrap.Modal.getOrCreateInstance(modalElemento);
                modalInstance.show();
            }

            elemento.style.pointerEvents = 'auto';
            elemento.classList.remove('cargando-modal');
        }, tiempoMs);
    }
</script>
@endonce
=======
</{!! $esSecretaria && $modoDashboard ? 'a' : 'button' !!}>
>>>>>>> origin/backend-Elias
