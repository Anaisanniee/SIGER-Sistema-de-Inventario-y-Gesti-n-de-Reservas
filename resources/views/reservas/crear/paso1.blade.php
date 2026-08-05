@extends('layouts.app')

@section('mostrarPerfil', 'false')
@section('mostrarBusqueda', 'false')

@section('content')
@php
    // Confiamos en el $tipoRecurso y en la colección de recursos que vienen seguros del controlador
    $tipoRecurso = $tipoRecurso ?? 'activo';
    $recursos = $recursos ?? collect();
@endphp

<link rel="stylesheet" href="{{ asset('css/components/stepper.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/detalle-recurso.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/reservas.css') }}">

<div class="contenedor-reserva-universal container py-4">

    {{-- 1. COMPONENTE STEPPER --}}
    <x-reservas.stepper paso="1" />

    {{-- 2. REJILLA PRINCIPAL CENTRADA --}}
    <div class="row justify-content-center mt-4">
        <div class="col-lg-8 col-md-10">

            {{-- Bloque Informativo del Recurso --}}
            <div class="tarjeta-reserva-siger p-4 shadow-sm bg-white rounded mb-4">
                <h3 class="fw-bold">Recursos Seleccionados</h3>
                <p class="subtitulo-tarjeta text-muted">Puedes volver atrás para cambiar los elementos</p>

                {{-- Lista de recursos seleccionados --}}
                <div class="lista-recursos-seleccionados mt-3">
                    @foreach($recursos as $recurso)
                        <div class="tarjeta-item-recurso mb-3 p-3 border rounded shadow-sm bg-light">
                            <div class="d-flex align-items-center">
                                <div class="icono-recurso me-3 text-primary fs-3">
                                    @if($tipoRecurso === 'aula')
                                        <i class="bi bi-door-open-fill"></i>
                                    @else
                                        <i class="bi bi-laptop"></i>
                                    @endif
                                </div>
                                <div>
                                    <span class="badge bg-secondary mb-1">{{ ucfirst($tipoRecurso) }}</span>
                                    <h5 class="mb-1 fw-bold">
                                        {{ $recurso->act_nombre ?? $recurso->aula_nombre ?? 'Sin nombre' }}
                                    </h5>
                                    <p class="text-muted mb-0 small">
                                        <strong>Serial / Código:</strong> {{ $recurso->act_serial ?? $recurso->aula_codigo ?? 'N/A' }} | 
                                        <strong>Marca:</strong> {{ $recurso->act_marca ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Opciones de Decisión y Formulario apuntando a la ruta limpia --}}
            <form action="{{ route('reservas.paso1.post') }}" method="POST" class="formulario-paso1">
                @csrf
                
                <input type="hidden" name="tipo_recurso" value="{{ $tipoRecurso }}">

                <div class="zona-decision mb-4">
                    <label class="tarjeta-radio d-block mb-2 p-3 border rounded shadow-sm bg-white cursor-pointer">
                        <div class="d-flex align-items-center">
                            <input type="radio" name="confirmacion_recurso" value="si" required checked class="me-3">
                            <div class="radio-contenido d-flex align-items-center">
                                <span class="radio-icono icono-exito text-success fs-4 me-3"><i class="fas fa-check-circle"></i></span>
                                <div>
                                    <strong class="d-block">Sí, es correcto</strong>
                                    <p class="mb-0 text-muted small">Continuar con la asignación de horarios.</p>
                                </div>
                            </div>
                        </div>
                    </label>

                    <label class="tarjeta-radio d-block p-3 border rounded shadow-sm bg-white cursor-pointer">
                        <div class="d-flex align-items-center">
                            <input type="radio" name="confirmacion_recurso" value="no" required class="me-3">
                            <div class="radio-contenido d-flex align-items-center">
                                <span class="radio-icono icono-error text-danger fs-4 me-3"><i class="fas fa-times-circle"></i></span>
                                <div>
                                    <strong class="d-block">No, me equivoqué</strong>
                                    <p class="mb-0 text-muted small">Regresar a la página principal.</p>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>

                <div class="contenedor-botones-paso1 text-end">
                    <x-botones.boton type="submit" class="btn-siger-accion btn btn-primary btn-lg w-100">
                        Siguiente Paso →
                    </x-botones.boton>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection