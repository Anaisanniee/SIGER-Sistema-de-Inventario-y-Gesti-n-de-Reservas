{{-- resources/views/components/tarjetas/tarjeta-acceso-rapido.blade.php --}}

@props([
    'href' => '#',
    'icono' => 'fas fa-link',
    'claseAcceso' => '', // Ej: 'acceso-reservas' o 'acceso-inventario'
    'titulo' => '',
    'descripcion' => ''
])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'tarjeta-acceso-rapido ' . $claseAcceso]) }}>
    <div class="acceso-icono">
        <i class="{{ $icono }}"></i>
    </div>
    <div class="acceso-texto">
        <h4>{{ $titulo }}</h4>
        <p>{{ $descripcion }}</p>
    </div>
    <i class="fas fa-chevron-right flecha-acceso"></i>
</a>