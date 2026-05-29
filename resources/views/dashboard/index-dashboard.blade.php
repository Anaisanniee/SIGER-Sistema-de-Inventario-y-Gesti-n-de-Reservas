@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/componentes/dashboard.css') }}">

<div class="container mt-2">
    <div class="row mb-4">
        <div class="col-12">
            <div class="banner-bienvenida text-white p-5 rounded-4 position-relative overflow-hidden shadow-sm">
                <h1 class="display-4 fw-bold mb-3">¡Bienvenido!</h1>
                <p class="fs-5 text-white-50 max-width-text">
                    Gestiona la reserva de aulas, equipos y recursos institucionales desde un solo lugar.
                </p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <a href="{{ url('/reservas') }}" class="text-decoration-none">
                <div class="tarjeta-acceso bg-white p-4 rounded-4 shadow-sm d-flex align-items-center gap-3 border-0 transition-card">
                    <div class="icono-wrapper bg-light p-3 rounded-3" style="font-size: 2rem;">
                        🏫
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Aulas</h5>
                        <p class="text-muted small m-0">Consultar disponibilidad</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="#" class="text-decoration-none">
                <div class="tarjeta-acceso bg-white p-4 rounded-4 shadow-sm d-flex align-items-center gap-3 border-0 transition-card">
                    <div class="icono-wrapper bg-light p-3 rounded-3" style="font-size: 2rem;">
                        💻
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Equipos</h5>
                        <p class="text-muted small m-0">Solicitar préstamo</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="#" class="text-decoration-none">
                <div class="tarjeta-acceso bg-white p-4 rounded-4 shadow-sm d-flex align-items-center gap-3 border-0 transition-card">
                    <div class="icono-wrapper bg-light p-3 rounded-3" style="font-size: 2rem;">
                        📦
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Categorías</h5>
                        <p class="text-muted small m-0">Ver categorías</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection