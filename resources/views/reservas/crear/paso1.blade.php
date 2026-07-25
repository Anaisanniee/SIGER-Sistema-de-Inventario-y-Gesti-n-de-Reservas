@extends('layouts.app')
@section('mostrarPerfil', 'false')
@section('mostrarBusqueda', 'false')
@section('content')
@php
    // Simulamos la variable para pruebas en rutas; tu compañero la enviará desde el controlador.
    // Puede ser 'activo' o 'aula'
    $tipoRecurso = isset($recurso) && is_object($recurso) ? $recurso->tipo : 'activo'; 
@endphp

<link rel="stylesheet" href="{{ asset('css/components/stepper.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/reservas.css') }}">

<div class="contenedor-reserva-universal">
    
    {{-- 1. COMPONENTE STEPPER (Barra de Progreso en Paso 1) --}}
    <x-reservas.stepper paso="1" />

    {{-- 2. BLOQUE CENTRAL DE CONFIRMACIÓN --}}
    <div class="tarjeta-reserva-siger tarjeta-confirmacion-paso1">
        
        <div class="encabezado-pregunta">
            <h2>¿Este es el recurso que desea reservar?</h2>
            <p class="subtitulo-tarjeta">Verifique las especificaciones técnicas del elemento seleccionado antes de continuar.</p>
        </div>

        {{-- Contenedor de la Ficha Técnica del Recurso --}}
        <div class="ficha-tecnica-recurso {{ $tipoRecurso === 'aula' ? 'borde-aula' : 'borde-activo' }}">
            <div class="info-principal-paso1">
                    <div class="icono-recurso-grande {{ $tipoRecurso === 'aula' ? 'icono-aula' : 'icono-activo' }}">
                        @if($tipoRecurso === 'aula')
                            <i class="bi bi-door-open-fill"></i>
                        @else
                            <i class="bi bi-laptop"></i>
                        @endif
                    </div>
                <div>
                    <span class="etiqueta-tipo-recurso">{{ ucfirst($tipoRecurso) }}</span>
                    <h1 class="nombre-recurso-paso1">
                        {{ isset($recurso) ? $recurso->nombres : ($tipoRecurso === 'aula' ? 'Laboratorio de Sistemas A' : 'Computador Portátil Dell') }}
                    </h1>
                </div>
            </div>

            {{-- Rejilla Dinámica de Atributos --}}
            <div class="grid-atributos-paso1">
                @if($tipoRecurso === 'aula')
                    {{-- ATRIBUTOS EXCLUSIVOS DE AULA --}}
                    <div class="item-atributo">
                        <span class="label-atributo">Capacidad</span>
                        <span class="valor-atributo">{{ isset($recurso) ? $recurso->capacidad : '35 Estudiantes' }}</span>
                    </div>
                    <div class="item-atributo">
                        <span class="label-atributo">Estado del Aula</span>
                        <span class="valor-atributo estado-badge disponible">Disponible</span>
                    </div>
                @else
                    {{-- ATRIBUTOS EXCLUSIVOS DE ACTIVO --}}
                    <div class="item-atributo">
                        <span class="label-atributo">Serial / Placa</span>
                        <span class="valor-atributo font-mono">{{ isset($recurso) ? $recurso->serial : 'DELL-5420-X92' }}</span>
                    </div>
                    <div class="item-atributo">
                        <span class="label-atributo">Marca</span>
                        <span class="valor-atributo">{{ isset($recurso) ? $recurso->marca : 'Dell Inspiron' }}</span>
                    </div>
                    <div class="item-atributo">
                        <span class="label-atributo">Estado Físico</span>
                        <span class="valor-atributo estado-badge funcional">Excelente Estado</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Opciones de Decisión y Formulario --}}
        <form action="#" method="POST" class="formulario-paso1">
            @csrf
            
            <div class="zona-decision">
                <label class="tarjeta-radio">
                    <input type="radio" name="confirmacion_recurso" value="si" required checked>
                    <div class="radio-contenido">
                        <span class="radio-icono">✅</span>
                        <div>
                            <strong>Sí, es correcto</strong>
                            <p>Continuar con la asignación de horarios.</p>
                        </div>
                    </div>
                </label>

                <label class="tarjeta-radio">
                    <input type="radio" name="confirmacion_recurso" value="no" required>
                    <div class="radio-contenido">
                        <span class="radio-icono">❌</span>
                        <div>
                            <strong>No, me equivoqué</strong>
                            <p>Regresar a la página principal.</p>
                        </div>
                    </div>
                </label>
            </div>

            {{-- Botón Siguiente alineado a la derecha --}}
            <div class="contenedor-botones-paso1">
                <x-botones.boton type="submit" class="btn-siger-accion btn btn-largo">
                    Siguiente Paso →
                </x-botones.boton>
            </div>
        </form>

    </div>
</div>
@endsection