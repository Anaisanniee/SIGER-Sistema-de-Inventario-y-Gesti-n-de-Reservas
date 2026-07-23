@props([
    'tipo' => 'advertencia',
    'titulo' => null,
    'descartable' => true
])

@php
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

{{-- Estilos propios encapsulados para que NUNCA vuelva a salir el recuadro feo --}}
<style>
    .alerta-siger {
        position: relative;
    }
    .btn-cerrar-alerta {
        position: absolute;
        top: 8px;
        right: 8px;
        background: transparent !important;
        border: none !important;
        outline: none !important;
        color: currentColor;
        opacity: 0.5;
        font-size: 0.85rem;
        cursor: pointer;
        padding: 4px;
        border-radius: 50%;
        line-height: 1;
        transition: opacity 0.2s, background-color 0.2s;
    }
    .btn-cerrar-alerta:hover {
        opacity: 1;
        background-color: rgba(0, 0, 0, 0.08) !important;
    }
</style>