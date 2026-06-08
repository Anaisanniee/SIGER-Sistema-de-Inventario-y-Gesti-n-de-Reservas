@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/componentes/tarjetas.css') }}">

<div class="container-fluid">
    <div class="row">
        
        @include('componentes.filtros-sidebar', [
            'action' => url('/reservas'),
            'config' => $filtroConfig
        ])

        <div id="seccionPrincipal" class="col-md-12 ps-4">
            
            <div class="d-flex gap-3 mb-4 me-4">
                
                <button type="button" class="btn btn-light border py-2 px-3 rounded-pill text-muted fw-bold" onclick="toggleFiltros()">
                    <i class="fas fa-filter me-2"></i> Filtros
                </button>

                <form action="{{ url('/reservas') }}" method="GET" class="position-relative flex-grow-1 m-0">
                    @foreach($filtroConfig as $campo)
                        @if(request($campo['name']))
                            <input type="hidden" name="{{ $campo['name'] }}" value="{{ request($campo['name']) }}">
                        @endif
                    @endforeach

                    <input type="text" name="buscar" value="{{ request('buscar') }}" class="form-control bg-light border-0 py-2 ps-3 pe-5 rounded-pill text-muted" placeholder="Buscar aula por nombre o codigo">
                    <button type="submit" class="position-absolute top-50 end-0 translate-middle-y me-3 bg-transparent border-0 text-secondary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <h2 class="fw-bold mb-1">Reservar aula</h2>
            <p class="text-muted mb-4">{{ $totalAulas ?? 0 }} aulas disponibles</p>

            <div class="grid-aulas">
                @forelse($aulas as $aula)
                    <div class="tarjeta {{ strtolower($aula->estado) == 'disponible' ? 'disponible' : 'ocupado' }}">
                        <div class="estado-tag">{{ $aula->estado }}</div>
                        <div class="icono-edificio">🏫</div>
                        <div class="info-cuerpo">
                            <h3>{{ $aula->nombre }} --- Bloque {{ $aula->bloque }}</h3>
                            <p class="detalle"><span class="icono">👥</span> {{ $aula->capacidad }} personas</p>
                            <p class="detalle"><span class="icono">📍</span> Piso {{ $aula->piso }}</p>
                        </div>
                        <div class="acciones">
                            @if(strtolower($aula->estado) == 'disponible')
                                <button class="btn-reservar">Reservar</button>
                            @else
                                <button class="btn-ocupado" disabled>Ocupado</button>
                            @endif
                            <button class="btn-ficha">Ver ficha técnica</button>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center mt-5">
                        <p class="text-muted fs-5">No se encontraron aulas con los filtros seleccionados.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<script>
function toggleFiltros() {
    const sidebar = document.getElementById('sidebarFiltros');
    const principal = document.getElementById('seccionPrincipal');

    if (sidebar.classList.contains('d-none')) {
        sidebar.classList.remove('d-none');
        principal.classList.replace('col-md-12', 'col-md-9');
    } else {
        sidebar.classList.add('d-none');
        principal.classList.replace('col-md-9', 'col-md-12');
    }
}

// Mantener abierto si hay algún parámetro de la configuración activo en la URL
document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    // Convertimos la configuración de PHP a un array de JS para leer los nombres de los campos
    const campos = @json(array_column($filtroConfig, 'name'));
    
    const tieneFiltrosActivos = campos.some(campo => urlParams.has(campo));

    if (tieneFiltrosActivos) {
        document.getElementById('sidebarFiltros').classList.remove('d-none');
        document.getElementById('seccionPrincipal').classList.replace('col-md-12', 'col-md-9');
    }
});
</script>
@endsection