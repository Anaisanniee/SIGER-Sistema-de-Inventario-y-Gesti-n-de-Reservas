@props(['opciones' => []])

<link rel="stylesheet" href="{{ asset('css/components/filtro-rapido.css') }}">

<div class="siger-filtro-horizontal-container">
    
    <div class="siger-filtro-scroll" id="filtro-rapido-componente">
        <button class="filtro-item-btn active" data-filtro="todos">Todos</button>
        
        @foreach($opciones as $opcion)
            <button class="filtro-item-btn" data-filtro="{{ Str::slug($opcion) }}">
                {{ $opcion }}
            </button>
        @endforeach
    </div>

    <div class="siger-filtro-select-container">
        <select class="form-select siger-filtro-select" id="filtro-rapido-select">
            <option value="todos">Filtrar por... (Todos)</option>
            @foreach($opciones as $opcion)
                <option value="{{ Str::slug($opcion) }}">{{ $opcion }}</option>
            @endforeach
        </select>
    </div>

</div>

<script src="{{ asset('js/componentes/filtro-rapido.js') }}"></script>
