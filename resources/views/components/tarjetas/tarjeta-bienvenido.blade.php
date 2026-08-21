{{-- resources/views/componentes/banner-bienvenida.blade.php --}}
@props([
    'titulo' => '¡Bienvenido!',
    'descripcion' => 'Gestiona la reserva de aulas, equipos y recursos institucionales desde un solo lugar.'
])

<div class="banner-bienvenida my-4 shadow-sm" style="background-color: #00b18d; color: white; padding: 45px 40px; border-radius: 16px;">
    <h1 class="fw-bold display-4 mb-3" style="font-weight: 800; letter-spacing: -1px;">
        {{ $titulo }}
    </h1>
    <p class="fs-5 m-0" style="max-width: 650px; opacity: 0.95; line-height: 1.5;">
        {{ $descripcion }}
    </p>
</div>