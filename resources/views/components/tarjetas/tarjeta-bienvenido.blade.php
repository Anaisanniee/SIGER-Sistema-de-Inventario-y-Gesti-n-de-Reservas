{{-- resources/views/components/banner-bienvenida.blade.php --}}
@props([
    'titulo' => '¡Bienvenido!',
    'descripcion' => 'Sistema institucional de inventario, activos y gestión de reservas en tiempo real.'
])

<div {{ $attributes->merge(['class' => 'banner-bienvenida']) }}>
    <div class="banner-patron-bg"></div>
    <div class="banner-contenido">
        <h1>{{ $titulo }}</h1>
        <p>{{ $descripcion }}</p>
    </div>
    <div class="banner-icono-decorativo">
        <i class="fas fa-shield-alt"></i>
    </div>
</div>

<style>
    /* ==========================================================================
   BANNER DE BIENVENIDA INSTITUCIONAL Y SOBRIO (CON TEXTURA)
   ========================================================================== */

.banner-bienvenida {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #0f766e 0%, #059669 50%, #10b981 100%);
    border-radius: 18px;
    padding: 2.5rem 3rem; /* Tamaño intermedio amplio */
    color: #ffffff;
    margin-bottom: 2rem;
    box-shadow: 0 10px 25px -5px rgba(5, 150, 105, 0.25),
                0 8px 10px -6px rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.15);
}

/* Textura sutil mediante SVG inline de rejilla geométrica */
.banner-patron-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    opacity: 0.08;
    background-image: radial-gradient(#ffffff 1px, transparent 1px), radial-gradient(#ffffff 1px, #059669 1px);
    background-size: 24px 24px;
    background-position: 0 0, 12px 12px;
    pointer-events: none;
}

/* Capa de contenido para jerarquía tipográfica */
.banner-contenido {
    position: relative;
    z-index: 2;
    max-width: 700px;
}

.banner-bienvenida h1 {
    font-family: var(--fuente-secundaria, inherit);
    font-size: 2.1rem;
    font-weight: 800;
    margin: 0 0 0.6rem 0;
    line-height: 1.25;
    letter-spacing: -0.02em;
    color: #ffffff;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.banner-bienvenida p {
    font-family: var(--fuente-principal, inherit);
    font-size: 1.05rem;
    color: rgba(255, 255, 255, 0.92);
    margin: 0;
    line-height: 1.5;
    font-weight: 400;
}

/* Elemento gráfico de marca de agua decorativo en la esquina */
.banner-icono-decorativo {
    position: absolute;
    right: 2rem;
    bottom: -1rem;
    font-size: 9.5rem;
    color: rgba(255, 255, 255, 0.07);
    transform: rotate(-15deg);
    pointer-events: none;
    z-index: 1;
}

/* Responsivo para móviles */
@media (max-width: 768px) {
    .banner-bienvenida {
        padding: 1.75rem 1.5rem;
    }
    .banner-bienvenida h1 {
        font-size: 1.5rem;
    }
    .banner-bienvenida p {
        font-size: 0.9rem;
    }
    .banner-icono-decorativo {
        display: none;
    }
}
</style>