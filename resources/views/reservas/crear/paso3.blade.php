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

    // Lista de activos adicionales (SI ES RESERVA MÚLTIPLE O TIENE INVENTARIO VINCULADO)
    // Si no hay nada o es una reserva simple, la dejas como array vacío: $activosIncluidos = [];
    $activosIncluidos = session('activos_reserva', [
        ['nombre' => 'VideoBeam Epson X41', 'codigo' => 'VB-001'],

    ]); 
@endphp

<link rel="stylesheet" href="{{ asset('css/components/stepper.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/reservas.css') }}">

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
        :activos="$activosIncluidos" 
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
                ⬅ Modificar Horario
            </x-botones.boton>
            
            <x-botones.boton type="submit" class="btn-siger-accion btn">
                Confirmar y Guardar Reserva
            </x-botones.boton>
        </div>
    </form>

</div>
@endsection