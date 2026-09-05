{{-- resources/views/components/tarjetas/tarjeta-acceso-rapido.blade.php --}}
@props([
    'href' => '#',
    'icono' => 'fas fa-link',
    'color' => 'verde', // Opciones: 'verde', 'amarillo', 'rojo', 'azul'
    'claseAcceso' => '',
    'titulo' => '',
    'descripcion' => ''
])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'tarjeta-acceso-rapido acceso-' . $color . ' ' . $claseAcceso]) }}>
    <div class="acceso-icono">
        <i class="{{ $icono }}"></i>
    </div>
    <div class="acceso-texto">
        <h4>{{ $titulo }}</h4>
        <p>{{ $descripcion }}</p>
    </div>
    <i class="fas fa-chevron-right flecha-acceso"></i>
</a>

<style>
    .tarjeta-acceso-rapido {
        display: flex;
        align-items: center;
        background-color: var(--color-fondo, #ffffff);
        border: 1px solid var(--color-borde, #e5e7eb);
        border-radius: var(--borde-radio, 8px);
        padding: var(--espaciado, 16px);
        text-decoration: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        width: 100%;
        box-sizing: border-box;
    }

    .tarjeta-acceso-rapido:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .acceso-icono {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-right: 16px;
        flex-shrink: 0;
    }

    .acceso-texto {
        flex-grow: 1;
        min-width: 0;
    }

    .acceso-texto h4 {
        font-family: var(--fuente-secundaria, inherit);
        color: var(--color-texto, #1f2937);
        margin: 0 0 4px 0;
        font-size: 1rem;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .acceso-texto p {
        color: var(--color-texto-secundario, #6b7280);
        margin: 0;
        font-size: 0.85rem;
        line-height: 1.3;
    }

    .flecha-acceso {
        color: var(--color-borde, #9ca3af);
        transition: transform 0.2s ease, color 0.2s ease;
        margin-left: 12px;
        flex-shrink: 0;
    }

    .tarjeta-acceso-rapido:hover .flecha-acceso {
        transform: translateX(4px);
        color: var(--color-texto, #1f2937);
    }

    /* ESQUEMAS DE COLOR CORREGIDOS SEGÚN TUS VARIABLES ORIGINALES */
    .acceso-verde {
        border-left: 4px solid var(--color-principal, #10b981);
    }
    .acceso-verde .acceso-icono {
        background-color: var(--color-verde-pastel, #bbf7d0);
        color: var(--color-estado-disponible, #22c55e);
    }

    /* AMARILLO: Mantenimiento */
    .acceso-amarillo {
        border-left: 4px solid var(--color-estado-en-mantenimiento, #e6cc66);
    }
    .acceso-amarillo .acceso-icono {
        background-color: var(--color-en-mantenimiento-pastel, #fef3c7);
        color: var(--color-estado-en-mantenimiento, #e6cc66);
    }

    /* ROJO: Dañado */
    .acceso-rojo {
        border-left: 4px solid var(--color-estado-dañado, #dc2626);
    }
    .acceso-rojo .acceso-icono {
        background-color: var(--color-dañado-pastel, #fee2e2);
        color: var(--color-estado-dañado, #dc2626);
    }

    /* AZUL: Reservado */
    .acceso-azul {
        border-left: 4px solid var(--color-estado-reservado, #3b82f6);
    }
    .acceso-azul .acceso-icono {
        background-color: var(--color-reservado-pastel, #dbeafe);
        color: var(--color-estado-reservado, #3b82f6);
    }

    /* MEDIA QUERIES PARA ADAPTABILIDAD RESPONSIVA */
    @media (max-width: 576px) {
        .tarjeta-acceso-rapido {
            padding: 12px;
        }

        .acceso-icono {
            width: 40px;
            height: 40px;
            font-size: 1.1rem;
            margin-right: 12px;
        }

        .acceso-texto h4 {
            font-size: 0.95rem;
            white-space: normal;
        }

        .acceso-texto p {
            font-size: 0.8rem;
        }
    }
</style>