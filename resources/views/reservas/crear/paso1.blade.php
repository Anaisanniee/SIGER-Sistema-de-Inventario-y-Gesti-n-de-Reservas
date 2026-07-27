@extends('layouts.app')
@section('mostrarPerfil', 'false')
@section('mostrarBusqueda', 'false')
@section('content')
@php
    // Confiamos plenamente en el $tipoRecurso que viene seguro del controlador
    $tipoRecurso = $tipoRecurso ?? 'activo';
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
                        {{ $tipoRecurso === 'aula' ? ($recurso->aula_nombre ?? 'Aula sin nombre') : ($recurso->act_nombre ?? 'Activo sin nombre') }}
                    </h1>
                </div>
            </div>

            {{-- Rejilla Dinámica de Atributos --}}
            <div class="grid-atributos-paso1">
                @if($tipoRecurso === 'aula')
                    {{-- ATRIBUTOS EXCLUSIVOS DE AULA --}}
                    <div class="item-atributo">
                        <span class="label-atributo">Capacidad</span>
                        <span class="valor-atributo">
                            {{ $recurso->aula_capacidad ?? ($recurso->capacidad ?? 'No registra') }} Estudiantes
                        </span>
                    </div>
                    <div class="item-atributo">
                        <span class="label-atributo">Estado del Aula</span>
                        <span class="valor-atributo estado-badge disponible">
                            {{ $recurso->aula_estado ?? ($recurso->estado ?? 'Disponible') }}
                        </span>
                    </div>
                @else
                    {{-- ATRIBUTOS EXCLUSIVOS DE ACTIVO --}}
                    <div class="item-atributo">
                        <span class="label-atributo">Serial / Placa</span>
                        <span class="valor-atributo font-mono">
                            {{ $recurso->act_serial ?? ($recurso->serial ?? ($recurso->placa ?? 'Sin serial')) }}
                        </span>
                    </div>
                    <div class="item-atributo">
                        <span class="label-atributo">Marca</span>
                        <span class="valor-atributo">
                            {{ $recurso->act_marca ?? ($recurso->marca ?? 'No registra') }}
                        </span>
                    </div>
                    <div class="item-atributo">
                        <span class="label-atributo">Estado Físico</span>
                        <span class="valor-atributo estado-badge funcional">
                            {{ $recurso->act_estado_fisico ?? ($recurso->estado_fisico ?? ($recurso->estado ?? 'Excelente Estado')) }}
                        </span>
                    </div>
                @endif
            </div>

        {{-- Opciones de Decisión y Formulario --}}
        <form action="{{ route('reservas.paso1.post', ['id' => $recurso->act_id ?? ($recurso->aula_id ?? 1)]) }}" method="POST" class="formulario-paso1">
            @csrf
            
            <input type="hidden" name="tipo_recurso" value="{{ $tipoRecurso }}">
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