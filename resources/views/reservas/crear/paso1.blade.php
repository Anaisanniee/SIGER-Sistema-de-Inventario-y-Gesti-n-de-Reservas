@extends('layouts.app')
<<<<<<< HEAD

=======
@section('rutaRegresar', auth()->user()->role === 'docente' 
    ? route('dashboard.docente', ['id' => auth()->id()]) 
    : route('dashboard.rectora', ['id' => auth()->id()]))
>>>>>>> origin/backend-Elias
@section('mostrarPerfil', 'false')
@section('mostrarBusqueda', 'false')

@section('content')
@php
<<<<<<< HEAD
    // Ejemplo de array de recursos (múltiples equipos para pruebas)
    $recursos = $recursos ?? [
        (object)[
            'tipo' => 'activo',
            'nombre' => 'Computador Portátil Dell Inspiron 15',
            'serial' => 'DELL-5420-X92',
            'marca' => 'Dell'
        ],
        (object)[
            'tipo' => 'activo',
            'nombre' => 'Video VideoProyector Epson PowerLite',
            'serial' => 'EPS-880-VP9',
            'marca' => 'Epson'
        ],
        (object)[
            'tipo' => 'activo',
            'nombre' => 'Sistema de Sonido / Cabina Cabina Bluetooth 8" ',
            'serial' => 'JBL-PARTY-04',
            'marca' => 'JBL'
        ],
        (object)[
            'tipo' => 'activo',
            'nombre' => 'Tableta de Dibujo Wacom Intuos',
            'serial' => 'WAC-CTL4100-88',
            'marca' => 'Wacom'
        ],
        (object)[
            'tipo' => 'activo',
            'nombre' => 'Camára Réflex Digital Canon EOS Rebel',
            'serial' => 'CAN-T7-4921',
            'marca' => 'Canon'
        ]
    ];
=======
    // Confiamos en el $tipoRecurso y en la colección de recursos que vienen seguros del controlador
    $tipoRecurso = $tipoRecurso ?? 'activo';
    $recursos = $recursos ?? collect();

    // Obtenemos el primer recurso de manera segura para las propiedades individuales (si solo es 1)
    $primerRecurso = $recursos->first();
>>>>>>> origin/backend-Elias
@endphp

<link rel="stylesheet" href="{{ asset('css/components/stepper.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/detalle-recurso.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/reservas.css') }}">

<<<<<<< HEAD
<div class="contenedor-reserva-universal">
=======
<div class="contenedor-reserva-universal container py-4">
>>>>>>> origin/backend-Elias

    {{-- 1. COMPONENTE STEPPER --}}
    <x-reservas.stepper paso="1" />

<<<<<<< HEAD
    {{-- 2. REJILLA PRINCIPAL --}}
    <div class="dashboard-reserva-grid paso1-grid">

        <div class="columna-formulario">

            {{-- Bloque Informativo del Recurso --}}
            <div class="tarjeta-reserva-siger">
                <h3>Recurso Seleccionado</h3>
                <p class="subtitulo-tarjeta">Puedes volver atrás para cambiar el elemento</p>

                {{-- TU COMPONENTE UNIFICADO: Él mismo sabe si renderizar 1 tarjeta o el acordeón --}}
                <x-reservas.detalle-recurso 
                    :tipoRecurso="$recursos[0]->tipo ?? 'activo'"
                    :recursoNombre="$recursos[0]->nombre ?? $recursos[0]->nombres ?? 'Recurso'"
                    :serial="$recursos[0]->serial ?? 'Sin Serial'"
                    :marca="$recursos[0]->marca ?? 'N/A'"
=======
    {{-- 2. REJILLA PRINCIPAL CENTRADA --}}
    <div class="row justify-content-center mt-4">
        <div class="col-lg-8 col-md-10">

            {{-- Bloque Informativo del Recurso --}}
            <div class="tarjeta-reserva-siger p-4 shadow-sm bg-white rounded mb-4">
                <h3 class="fw-bold">Recursos Seleccionados</h3>
                <p class="subtitulo-tarjeta text-muted">Puedes volver atrás para cambiar los elementos</p>

                {{-- COMPONENTE UNIFICADO DE TU COMPAÑERA: Maneja automáticamente 1 recurso o el acordeón múltiple --}}
                <x-reservas.detalle-recurso 
                    :tipoRecurso="$tipoRecurso"
                    :recursoNombre="$primerRecurso->act_nombre ?? $primerRecurso->aula_nombre ?? 'Recurso'"
                    :serial="$primerRecurso->act_serial ?? $primerRecurso->aula_codigo ?? 'Sin Serial'"
                    :marca="$primerRecurso->act_marca ?? 'N/A'"
                    :capacidad="$primerRecurso->aula_capacidad ?? 'N/A'"
>>>>>>> origin/backend-Elias
                    :recursos="$recursos" 
                />
            </div>

<<<<<<< HEAD
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
=======
            {{-- Opciones de Decisión y Formulario apuntando a tu ruta de lógica --}}
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
>>>>>>> origin/backend-Elias
                            </div>
                        </div>
                    </label>

<<<<<<< HEAD
                    <label class="tarjeta-radio">
                        <input type="radio" name="confirmacion_recurso" value="no" required>
                        <div class="radio-contenido">
                            <span class="radio-icono icono-error"><i class="fas fa-times"></i></span>
                            <div>
                                <strong>No, me equivoqué</strong>
                                <p>Regresar a la página principal.</p>
=======
                    <label class="tarjeta-radio d-block p-3 border rounded shadow-sm bg-white cursor-pointer">
                        <div class="d-flex align-items-center">
                            <input type="radio" name="confirmacion_recurso" value="no" required class="me-3">
                            <div class="radio-contenido d-flex align-items-center">
                                <span class="radio-icono icono-error text-danger fs-4 me-3"><i class="fas fa-times-circle"></i></span>
                                <div>
                                    <strong class="d-block">No, me equivoqué</strong>
                                    <p class="mb-0 text-muted small">Regresar a la página principal.</p>
                                </div>
>>>>>>> origin/backend-Elias
                            </div>
                        </div>
                    </label>
                </div>

<<<<<<< HEAD
                <div class="contenedor-botones-paso1">
                    <x-botones.boton type="submit" class="btn-siger-accion btn btn-largo">
=======
                <div class="contenedor-botones-paso1 text-end">
                    <x-botones.boton type="submit" class="btn-siger-accion btn btn-primary btn-lg w-100">
>>>>>>> origin/backend-Elias
                        Siguiente Paso →
                    </x-botones.boton>
                </div>
            </form>

        </div>
<<<<<<< HEAD

=======
>>>>>>> origin/backend-Elias
    </div>

</div>
@endsection