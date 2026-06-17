@props([
    'class' => 'btn',
    'target' => 'null',
    'type' => 'button'
])

<button 
    type="{{$type}}"

    class="{{ $class }} {{ $attributes->get('class') }}"

     @if($target && $target != 'null')
        data-bs-toggle="modal"
        data-bs-target="#{{ $target }}"
    @endif

    {{ $attributes->whereDoesntStartWith('type') }}
>

    {{ $slot }}

</button>