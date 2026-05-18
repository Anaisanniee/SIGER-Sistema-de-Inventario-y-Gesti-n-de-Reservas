@props([
    'clase' => 'btn',
    'target' => 'null'
])

<button
    type="button"

    class="{{ $clase }}"

     @if($target)
        data-bs-toggle="modal"
        data-bs-target="#{{ $target }}"
    @endif

    {{ $attributes }}
>

    {{ $slot }}

</button>