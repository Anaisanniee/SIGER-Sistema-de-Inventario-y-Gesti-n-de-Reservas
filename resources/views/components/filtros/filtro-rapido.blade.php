@props(['opciones' => []])

<link rel="stylesheet" href="{{ asset('css/components/filtro-rapido.css') }}">

<div class="siger-filtro-horizontal-container">
    <div class="siger-filtro-scroll" id="filtro-rapido-componente">
        <button class="filtro-item-btn active" data-filtro="todos">Todos</button>
        @foreach($opciones as $opcion)
            <button class="filtro-item-btn" data-filtro="{{ Str::slug($opcion) }}">{{ $opcion }}</button>
        @endforeach
    </div>
</div>

<script src="{{ asset('js/componentes/filtro-rapido.js') }}"></script>
