@props([
    'class' => 'btn',
    'target' => 'null',
    'type' => 'button',
    'url' => null // Agregamos la propiedad URL
])

@if($url)
    {{-- Si hay una URL, renderizamos un enlace --}}
    <a href="{{ $url }}" class="{{ $class }} {{ $attributes->get('class') }}" {{ $attributes->whereDoesntStartWith(['type', 'url']) }}>
        {{ $slot }}
    </a>
@else
    {{-- Si no hay URL, seguimos usando el botón (para modales) --}}
    <button 
        type="{{$type}}"
        class="{{ $class }} {{ $attributes->get('class') }}"
        @if($target && $target != 'null')
            data-bs-toggle="modal"
            data-bs-target="#{{ $target }}"
        @endif
        {{ $attributes->whereDoesntStartWith(['type', 'url']) }}
    >
        {{ $slot }}
    </button>
@endif
