{{-- resources/views/components/alertas/notificacion.blade.php --}}
@props([
    'tipo' => 'advertencia', // peligro, advertencia, exito, info
    'titulo' => null,
    'descartable' => true
])

@php
    // Mapeo de íconos según el tipo de alerta
    $iconos = [
        'peligro'     => 'fas fa-exclamation-circle',
        'advertencia' => 'fas fa-tools',
        'exito'       => 'fas fa-check-circle',
        'info'        => 'fas fa-info-circle'
    ];
    $iconoClase = $iconos[$tipo] ?? 'fas fa-bell';
@endphp

<div class="alerta-siger alerta-{{ $tipo }}" data-alerta-item>
    <i class="{{ $iconoClase }} icono-alerta"></i>
    
    <div class="alerta-contenido-texto">
        @if($titulo)
            <h5>{{ $titulo }}</h5>
        @endif
        <p>{{ $slot }}</p>
    </div>

    @if($descartable)
        <button type="button" class="btn-cerrar-alerta" data-btn-cerrar title="Descartar">
            <i class="fas fa-times"></i>
        </button>
    @endif
</div>