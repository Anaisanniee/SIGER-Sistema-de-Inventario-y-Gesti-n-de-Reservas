@props([
    'clase' => 'btn',
    'target' => 'null',
    'type' => 'button'
])

<button 
    type="{{$type}}"

    class="{{ $clase }}"

     @if($target && $target != 'null')
        data-bs-toggle="modal"
        data-bs-target="#{{ $target }}"
    @endif

    {{ $attributes->whereDoesntStartWith('type') }}
>

    {{ $slot }}

</button>