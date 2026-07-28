@extends('layouts.app')

@section('mostrarPerfil', 'false')
@section('mostrarBusqueda', 'false')

@section('content')
@php
    $recursos = [
        (object)[
            'tipo' => 'activo',
            'nombres' => 'Computador Portátil Dell Inspiron',
            'serial' => 'DELL-5420-X92',
            'marca' => 'Dell'
        ],
        (object)[
            'tipo' => 'activo',
            'nombres' => 'Video Beam Epson X41',
            'serial' => 'EPS-8832-A1',
            'marca' => 'Epson'
        ],
        (object)[
            'tipo' => 'aula',
            'nombres' => 'Laboratorio de Sistemas A',
            'capacidad' => '35 Estudiantes'
        ]
    ];
    // Evaluamos si vienen varios recursos o solo uno
    $esMultiple = isset($recursos) && is_array($recursos) && count($recursos) > 1;
@endphp

<link rel="stylesheet" href="{{ asset('css/components/stepper.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/detalle-recurso.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/reservas.css') }}">

<div class="contenedor-reserva-universal">

    {{-- 1. COMPONENTE STEPPER (Barra de Progreso) --}}
    <x-reservas.stepper paso="1" />

    {{-- 2. REJILLA DE DISTRIBUCIÓN PRINCIPAL (Añadida clase paso1-grid para centrado único) --}}
    <div class="dashboard-reserva-grid paso1-grid">

        {{-- COLUMNA IZQUIERDA: CONFIGURACIÓN Y RECURSO --}}
        <div class="columna-formulario">

            {{-- Bloque Informativo del Recurso --}}
            <div class="tarjeta-reserva-siger">
                <h3>Recurso Seleccionado</h3>
                <p class="subtitulo-tarjeta">Puedes volver atrás para cambiar el elemento</p>

                @if(!$esMultiple)
                    {{-- CASO 1: UN SOLO RECURSO -> Muestra el componente predeterminado --}}
                    <x-reservas.detalle-recurso 
                        :nombre="isset($recurso) ? $recurso->nombres : 'Computador Dell Inspiron'" 
                        :detalle="isset($recurso) ? $recurso->serial : '#EQ-01 --- Windows 11'" 
                        estado="Disponible" 
                    />
                @else
                    {{-- CASO 2: MÚLTIPLES RECURSOS -> Muestra el acordeón desplegable --}}
                    <div class="accordion mt-2" id="acordeonRecursosReserva">
                        <div class="accordion-item-siger">
                            <button class="accordion-trigger" type="button" data-bs-toggle="collapse" data-bs-target="#colapsoRecursosReserva" aria-expanded="false">
                                <span class="trigger-content">
                                    <span class="icon-box"><i class="bi bi-layers-fill"></i></span>
                                    <span class="text">Ver lista de recursos</span>
                                </span>
                                <span class="badge-conteo">{{ count($recursos) }}</span>
                            </button>

                            <div id="colapsoRecursosReserva" class="collapse" data-bs-parent="#acordeonRecursosReserva">
                                <div class="accordion-body-siger">
                                    <ul class="lista-activos-siger">
                                        @foreach($recursos as $item)
                                            <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                <div>
                                                    <strong class="d-block text-dark">{{ $item->nombres ?? 'Recurso' }}</strong>
                                                    <small class="text-muted">{{ $item->serial ?? $item->marca ?? 'Sin detalles' }}</small>
                                                </div>
                                                <span class="badge bg-success bg-opacity-10 text-success">
                                                    {{ ucfirst($item->tipo ?? 'activo') }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Opciones de Decisión y Formulario --}}
            <form action="#" method="POST" class="formulario-paso1 mt-4">
                @csrf
                
                <div class="zona-decision">
                    <label class="tarjeta-radio">
                        <input type="radio" name="confirmacion_recurso" value="si" required checked>
                        <div class="radio-contenido">
                            <span class="radio-icono icono-exito"><i class="fas fa-check"></i></span>
                            <div>
                                <strong>Sí, es correcto</strong>
                                <p>Continuar con la asignación de horarios.</p>
                            </div>
                        </div>
                    </label>

                    <label class="tarjeta-radio">
                        <input type="radio" name="confirmacion_recurso" value="no" required>
                        <div class="radio-contenido">
                            <span class="radio-icono icono-error"><i class="fas fa-times"></i></span>
                            <div>
                                <strong>No, me equivoqué</strong>
                                <p>Regresar a la página principal.</p>
                            </div>
                        </div>
                    </label>
                </div>

                {{-- Botón Siguiente --}}
                <div class="contenedor-botones-paso1">
                    <x-botones.boton type="submit" class="btn-siger-accion btn btn-largo">
                        Siguiente Paso →
                    </x-botones.boton>
                </div>
            </form>

        </div>

    </div> {{-- Cierre de la rejilla principal --}}

</div>
@endsection