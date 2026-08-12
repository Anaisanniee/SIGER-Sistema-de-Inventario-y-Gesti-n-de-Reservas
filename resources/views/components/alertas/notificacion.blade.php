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

{{-- Estilos encapsulados mejorados --}}
<style>
    .alerta-siger {
        position: relative;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        border-radius: 8px;
        border-left: 4px solid transparent;
        font-family: inherit;
        font-size: 0.9rem;
        line-height: 1.4;
        margin-bottom: 12px;
        transition: all 0.2s ease-in-out;
    }

    /* Alineación de icono y texto */
    .alerta-siger .icono-alerta {
        font-size: 1.25rem;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .alerta-contenido-texto {
        flex: 1;
        padding-right: 20px; /* Espacio para que el texto no choque con la X */
    }

    .alerta-contenido-texto h5 {
        margin: 0 0 4px 0;
        font-size: 0.95rem;
        font-weight: 600;
        line-height: 1.2;
    }

    .alerta-contenido-texto p {
        margin: 0;
    }

    /* Botón cerrar descartable */
    .btn-cerrar-alerta {
        position: absolute;
        top: 10px;
        right: 10px;
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

    /* =========================================================
       ESTILOS POR TIPO DE ALERTA (Paleta de colores limpia)
       ========================================================= */

    /* Peligro / Error / Rechazado */
    .alerta-siger.alerta-peligro {
        background-color: var(--color-dañado-pastel);
        border-left-color: #ef4444;
        color: #991b1b;
    }
    .alerta-siger.alerta-peligro .icono-alerta {
        color: var(--color-estado-dañado);
    }

    /* Advertencia / Alerta / Pendiente */
    .alerta-siger.alerta-advertencia {
        background-color: var(--color-en-mantenimiento-pastel);
        border-left-color: var(--color-estado-en-mantenimiento);
        color: #92400e;
    }
    .alerta-siger.alerta-advertencia .icono-alerta {
        color: #d97706;
    }

    /* Éxito / Aprobado */
    .alerta-siger.alerta-exito {
        background-color: var(--color-disponible-pastel);
        border-left-color: var(--color-estado-disponible);
        color: var(--principal-secundario);
    }
    .alerta-siger.alerta-exito .icono-alerta {
        color:var( --color-principal);
    }

    /* Información */
    .alerta-siger.alerta-info {
        background-color: var(--color-reservado-pastel);
        border-left-color: var(--color-estado-reservado);
        color: #1e40af;
    }
    .alerta-siger.alerta-info .icono-alerta {
        color: #2563eb;
    }
</style>