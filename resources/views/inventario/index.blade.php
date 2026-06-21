@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    
    <div class="mb-4">
        <h2 class="fw-bold m-0" style="color: #2d3748;">Préstamos de equipos</h2>
        <p class="text-muted m-0 small fw-medium">Solicita equipos disponibles para tus clases.</p>
    </div>

    <div class="row g-3 mb-4 align-items-center">
        <div class="col-auto">
            <button  type="submit" class="btn btn-light border fw-bold rounded-3 px-3 py-2 text-secondary shadow-sm" data-bs-toggle="collapse" data-bs-target="#collapseFiltros" aria-expanded="false" aria-controls="collapseFiltros">
                🔑 Filtros
            </button>
        </div>
        <div class="col">
            <form action="{{ route('activos.index') }}" method="GET" class="row g-2 shadow-sm p-3 bg-white rounded border">
                <input type="hidden" name="tipo" value="{{ request('tipo') }}">
                <input type="hidden" name="disponibilidad" value="{{ request('disponibilidad') }}">
                
                <input type="text" name="buscar" class="form-control bg-light border-0 py-2 ps-3 pe-5 rounded-pill shadow-sm" placeholder="Buscar equipo por nombre o código..." value="{{ request('buscar') }}">
                <button type="submit" class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-1 border-0 bg-transparent text-muted">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- @include('componentes.filtro-prestamos') --}}

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @forelse($activos as $activo)
            <div class="col">
                <div class="card h-100 shadow-sm border border-1 text-center rounded-4 overflow-hidden" style="border-color: #e2e8f0 !important;">
                    
                    <div class="p-4 position-relative d-flex align-items-center justify-content-center" 
                         style="background-color: {{ $activo['estado'] == 'Disponible' ? '#5ce1b6' : '#ff7657' }}; min-height: 120px;">
                        <i class="fas {{ $activo['icono'] }} text-white" style="font-size: 3rem;"></i>
                        <span class="badge position-absolute top-0 end-0 m-2 rounded-pill bg-black bg-opacity-20 small">
                            {{ $activo['estado'] }}
                        </span>
                    </div>

                    <div class="card-body d-flex flex-column p-3 text-start">
                        <h5 class="card-title fw-bold text-dark mb-1" style="font-size: 1.1rem;">{{ $activo['nombre'] }}</h5>
                        <p class="text-muted small mb-2 fw-semibold" style="color: #a0aec0 !important;">{{ $activo['codigo'] }}</p>
                        
                        <div class="text-muted mb-3 small flex-grow-1" style="color: #718096 !important; font-size: 0.85rem;">
                            @foreach($activo['detalles'] ?? [] as $detalle)
                                <div class="mb-1">🔹 {{ $detalle }}</div>
                            @endforeach
                        </div>

                        <div class="d-grid gap-2 mt-auto">
                            @if($activo['estado'] == 'Disponible')
                                <button class="btn btn-success fw-bold py-2 rounded-3" style="background-color: #00b18d; border: none;">Reservar</button>
                            @else
                                <button class="btn btn-danger fw-bold py-2 rounded-3 disabled" style="background-color: #ff4d4d; border: none; opacity: 1;">Ocupado</button>
                            @endif
                            <button class="btn btn-light fw-bold py-2 rounded-3 text-dark border-1 border-secondary border-opacity-10" style="background-color: #f8f9fa;">Ficha técnica</button>
                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted m-0 fw-medium">No se encontraron equipos con los filtros seleccionados.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection