@extends('layouts.app')
@section('mostrarPerfil', 'false')
@section('mostrarBusqueda', 'false')

@section('content')
@php
    // Simulamos las variables para pruebas (o las que mande tu backend desde el controlador / sesión)
    $tipoRecurso = isset($recurso) && is_object($recurso) ? $recurso->tipo : 'aula';
    $recursoNombre = isset($recurso) ? $recurso->nombres : ($tipoRecurso === 'aula' ? 'Laboratorio de Sistemas A' : 'Computador Portátil Dell');
    $capacidad = isset($recurso) ? $recurso->capacidad : '35 Estudiantes';
    $serial = isset($recurso) ? $recurso->serial : 'DELL-5420-X92';
    $marca = isset($recurso) ? $recurso->marca : 'Dell Inspiron';
@endphp

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

    // Asignamos para compatibilidad si el backend envía $activosIncluidos
    $activosIncluidos = $activosIncluidos ?? $recursos;
@endphp

<link rel="stylesheet" href="{{ asset('css/components/stepper.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/detalle-recurso.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/reservas.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/resumen-reserva.css') }}">

<div class="contenedor-reserva-universal">
    
    {{-- 1. COMPONENTE STEPPER --}}
    <x-reservas.stepper paso="3" />

    {{-- 2. COMPONENTE DE RESUMEN FINAL --}}
    <x-reservas.resumen-reserva 
        :tipoRecurso="$tipoRecurso"
        :solicitante="Auth::user()->nombres ?? 'Docente Solicitante'"
        :identificacion="Auth::user()->identificacion ?? '1.004.234.XXX'"
        :email="Auth::user()->email ?? 'docente@colegio.edu.co'"
        :recursoNombre="$recursoNombre"
        :capacidad="$capacidad"
        :serial="$serial"
        :marca="$marca"
        :fechaInicio="session('res_fecha_inicio') ?? '2026-07-10'"
        :horaInicio="session('res_hora_inicio') ?? '07:00 AM'"
        :fechaFin="session('res_fecha_fin') ?? '2026-07-10'"
        :horaFin="session('res_hora_fin') ?? '09:30 AM'"
        :aulaUso="session('aula_uso') ?? 'Salón 601'"
        :recursos="$recursos" 
    />

    {{-- 3. FORMULARIO FINAL DE ENVÍO --}}
    <form action="#" method="POST" class="formulario-paso3">
        @csrf
        
        <div class="notificacion-alerta-siger margin-top-main">
            <p>⚠️ Al presionar "Confirmar y Guardar", la solicitud se mostrará pendiente para aprobación.</p>
        </div>

        {{-- Botones de Navegación --}}
        <div class="contenedor-botones-paso3">
            <x-botones.boton type="button" class="btn-siger-accion btn btn-azul" onclick="window.history.back();">
                ⬅ Modificar
            </x-botones.boton>
            
            <x-botones.boton type="submit" class="btn-siger-accion btn">
                Confirmar y Guardar Reserva
            </x-botones.boton>
        </div>
    </form>

</div>
@endsection