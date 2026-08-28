<<<<<<< HEAD
=======
{{-- resources/views/components/botones/boton.blade.php --}}
>>>>>>> origin/backend-Elias
@props([
    'class' => 'btn',
    'target' => 'null',
    'type' => 'button',
<<<<<<< HEAD
    'url' => null // Agregamos la propiedad URL
=======
    'url' => null
>>>>>>> origin/backend-Elias
])

@if($url)
    {{-- Si hay una URL, renderizamos un enlace --}}
<<<<<<< HEAD
    <a href="{{ $url }}" class="{{ $class }} {{ $attributes->get('class') }}" {{ $attributes->whereDoesntStartWith(['type', 'url']) }}>
        {{ $slot }}
    </a>
@else
    {{-- Si no hay URL, seguimos usando el botón (para modales) --}}
    <button 
        type="{{$type}}"
        class="{{ $class }} {{ $attributes->get('class') }}"
=======
    <a href="{{ $url }}" {{ $attributes->merge(['class' => $class]) }}>
        {{ $slot }}
    </a>
@else
    {{-- Si no hay URL, renderizamos un botón normal o para modales --}}
    <button 
        type="{{ $type }}"
>>>>>>> origin/backend-Elias
        @if($target && $target != 'null')
            data-bs-toggle="modal"
            data-bs-target="#{{ $target }}"
        @endif
<<<<<<< HEAD
        {{ $attributes->whereDoesntStartWith(['type', 'url']) }}
    >
        {{ $slot }}
    </button>
@endif
=======
        {{ $attributes->merge(['class' => $class]) }}
    >
        {{ $slot }}
    </button>
@endif
>>>>>>> origin/backend-Elias
