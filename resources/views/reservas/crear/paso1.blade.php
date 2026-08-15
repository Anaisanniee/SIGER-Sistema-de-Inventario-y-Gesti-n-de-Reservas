@extends('layouts.app')

@section('mostrarPerfil', 'false')
@section('mostrarBusqueda', 'false')

@section('content')
@php
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
@endphp

<link rel="stylesheet" href="{{ asset('css/components/stepper.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/detalle-recurso.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/reservas.css') }}">

<div class="contenedor-reserva-universal">

    {{-- 1. COMPONENTE STEPPER --}}
    <x-reservas.stepper paso="1" />

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
                    :recursos="$recursos" 
                />
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

                <div class="contenedor-botones-paso1">
                    <x-botones.boton type="submit" class="btn-siger-accion btn btn-largo">
                        Siguiente Paso →
                    </x-botones.boton>
                </div>
            </form>

        </div>

    </div>

</div>
@endsection