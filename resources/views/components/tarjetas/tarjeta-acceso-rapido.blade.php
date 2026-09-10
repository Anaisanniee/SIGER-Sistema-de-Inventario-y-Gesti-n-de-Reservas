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
        border: 1px solid var(--color-borde, #e2e8f0);
        border-radius: var(--borde-radio, 12px);
        padding: 1rem;
        text-decoration: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        width: 100%;
        box-sizing: border-box;
        min-height: 72px; /* Uniforma la altura de todas las tarjetas */
    }

    .tarjeta-acceso-rapido:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        border-color: #cbd5e1;
    }

    /* Corrección de centrado absoluto del icono dentro del círculo */
    .acceso-icono {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 1.15rem;
        margin-right: 14px;
        flex-shrink: 0;
        line-height: 1 !important;
    }

    .acceso-icono i {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .acceso-texto {
        flex-grow: 1;
        min-width: 0;
    }

    .acceso-texto h4 {
        font-family: var(--fuente-secundaria, inherit);
        color: var(--color-texto, #0f172a);
        margin: 0 0 2px 0;
        font-size: 0.95rem;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .acceso-texto p {
        color: var(--color-texto-secundario, #64748b);
        margin: 0;
        font-size: 0.82rem;
        line-height: 1.3;
    }

    .flecha-acceso {
        color: var(--color-borde, #94a3b8);
        transition: transform 0.2s ease, color 0.2s ease;
        margin-left: 12px;
        flex-shrink: 0;
        font-size: 0.9rem;
    }

    .tarjeta-acceso-rapido:hover .flecha-acceso {
        transform: translateX(4px);
        color: var(--color-texto, #0f172a);
    }

    /* Esquemas de color */
    .acceso-verde { border-left: 4px solid var(--color-principal, #10b981); }
    .acceso-verde .acceso-icono { background-color: var(--color-verde-pastel, #d1fae5); color: var(--color-estado-disponible, #10b981); }

    .acceso-amarillo { border-left: 4px solid var(--color-estado-en-mantenimiento, #f59e0b); }
    .acceso-amarillo .acceso-icono { background-color: var(--color-en-mantenimiento-pastel, #fef3c7); color: var(--color-estado-en-mantenimiento, #d97706); }

    .acceso-rojo { border-left: 4px solid var(--color-estado-dañado, #ef4444); }
    .acceso-rojo .acceso-icono { background-color: var(--color-dañado-pastel, #fee2e2); color: var(--color-estado-dañado, #dc2626); }

    .acceso-azul { border-left: 4px solid var(--color-estado-reservado, #3b82f6); }
    .acceso-azul .acceso-icono { background-color: var(--color-reservado-pastel, #dbeafe); color: var(--color-estado-reservado, #2563eb); }
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

    
