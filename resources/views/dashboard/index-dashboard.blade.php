@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            
            @include('componentes.banner-bienvenida', [
                'titulo' => '¡Bienvenido!',
                'descripcion' => 'Gestiona la reserva de aulas, equipos y recursos institucionales desde un solo lugar.'
            ])

        </div>
    </div>

    <div class="row mt-4 g-4">
        <div class="col-md-4">
            <a href="{{ url('/reservas') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm p-4 rounded-4 background-hover" style="transition: transform 0.2s;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="fs-1 p-3 bg-light rounded-4">🏫</div>
                        <div>
                            <h4 class="fw-bold text-dark m-0">Aulas</h4>
                            <p class="text-muted small m-0">Consultar disponibilidad</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm p-4 rounded-4 background-hover" style="transition: transform 0.2s;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="fs-1 p-3 bg-light rounded-4">💻</div>
                        <div>
                            <h4 class="fw-bold text-dark m-0">Equipos</h4>
                            <p class="text-muted small m-0">Solicitar préstamo</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm p-4 rounded-4 background-hover" style="transition: transform 0.2s;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="fs-1 p-3 bg-light rounded-4">📦</div>
                        <div>
                            <h4 class="fw-bold text-dark m-0">Categorías</h4>
                            <p class="text-muted small m-0">Ver categorías</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
/* Efecto sutil para resaltar las tarjetas al pasar el mouse */
.background-hover:hover {
    transform: translateY(-5px);
    background-color: #f8f9fa;
}
</style>
@endsection