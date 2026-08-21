{{-- resources/views/components/botones/boton-logout.blade.php --}}

@props([
    'texto' => 'Cerrar Sesión',
    'class' => 'btn-siger-accion btn-rojo'
])

<form method="POST" action="{{ route('logout') }}" class="m-0 p-0 w-100 d-block logout-form">
    @csrf
    <x-botones.boton type="submit" class="{{ $class }} w-100 d-flex align-items-center justify-content-center gap-2">
        <i class="bi bi-box-arrow-right"></i>
        <span>{{ $texto }}</span>
    </x-botones.boton>
</form>