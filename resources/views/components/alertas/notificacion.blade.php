@props(['tipo' => 'exito'])

<div class="alerta-siger alerta-{{ $tipo }}">
    <span class="alerta-icono">
        {{ $tipo === 'exito' ? '✅' : '⚠️' }}
    </span>
    <div class="alerta-contenido">
        <p class="alerta-texto">
            {{ $slot }}
        </p>
    </div>
</div>