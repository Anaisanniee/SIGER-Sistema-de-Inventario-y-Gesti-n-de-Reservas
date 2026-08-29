{{-- resources/views/components/botones/boton.blade.php --}}
@props([
    'class' => 'btn',
    'target' => 'null',
    'type' => 'button',
    'url' => null
])

@if($url)
    {{-- Si hay una URL, renderizamos un enlace --}}
    <a href="{{ $url }}" {{ $attributes->merge(['class' => $class]) }}>
        {{ $slot }}
    </a>
@else
    {{-- Si no hay URL, renderizamos un botón normal o para modales --}}
    <button 
        type="{{ $type }}"
        @if($target && $target != 'null')
            data-bs-toggle="modal"
            data-bs-target="#{{ $target }}"
        @endif
        {{ $attributes->merge(['class' => $class]) }}
    >
        {{ $slot }}
    </button>
@endif