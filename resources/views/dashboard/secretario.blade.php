@extends('layouts.app') 

@section('content')
@section('mostrarRegresar', 'false')

<link rel="stylesheet" href="{{ asset('css/pages/dashboard-secretario.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/tarjeta-reserva.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/resumen-reserva.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/detalle-recurso.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/reservas.css') }}">
@php
    $esAdmin = Auth::user()->esAdmin ?? true;
    $reservasSimuladas = [
        (object)[
            'id' => 1,
            'recurso_foto' => null,
            'recurso_nombre' => 'Computador Dell Inspiron',
            'estado' => 'pendiente',
            'usuario_nombre' => 'Docente Carlos Mendoza',
            'fecha' => '2026-07-12',
            'hora_inicio' => '8:00 AM',
            'hora_fin' => '10:00 AM',
            'ubicacion' => 'Aula 101'
        ],
        (object)[
            'id' => 2,
            'recurso_foto' => null,
            'recurso_nombre' => 'Videobeam Epson X41',
            'estado' => 'pendiente',
            'usuario_nombre' => 'Docente María Alejandra',
            'fecha' => '2026-07-13',
            'hora_inicio' => '10:00 AM',
            'hora_fin' => '12:00 PM',
            'ubicacion' => 'Laboratorio de Sistemas'
        ]
    ];
@endphp

{{--- 1. TARJETA DE BIENVENIDA ---}}
@include('components.tarjetas.tarjeta-bienvenido', [
    'titulo' => 'Panel de Control - SIGER',   
    'descripcion' => 'Sistema institucional de inventario, activos y gestión de reservas en tiempo real.'
])

{{--- 2. SECCIÓN SUPERIOR: ACCESOS Y ALERTAS (Gobernados por .dashboard-grid) ---}}
<div class="dashboard-grid">
    
    <!-- COLUMNA 1: ACCESOS RÁPIDOS -->
    <div class="dashboard-columna">
        <h3 class="dashboard-subtitulo">Módulos Disponibles</h3>
        <div class="contenedor-accesos">
            
            <!-- Acceso a Reservas -->
            <a href="{{ url('/reservas/gestion') }}" class="tarjeta-acceso-rapido acceso-reservas">
                <div class="acceso-icono">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="acceso-texto">
                    <h4>Gestión de Reservas</h4>
                    <p>Revisa solicitudes, aprueba, rechaza y controla las agendas del día.</p>
                </div>
                <i class="fas fa-chevron-right flecha-acceso"></i>
            </a>

            <!-- Acceso a Inventario -->
            <a href="{{ url('/inventario') }}" class="tarjeta-acceso-rapido acceso-inventario">
                <div class="acceso-icono">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="acceso-texto">
                    <h4>Gestión de Inventario</h4>
                    <p>Controla las aulas, equipos tecnológicos y el estado de los activos.</p>
                </div>
                <i class="fas fa-chevron-right flecha-acceso"></i>
            </a>

        </div>
    </div>

    <!-- COLUMNA 2: ALERTAS DEL SISTEMA -->
    <div class="dashboard-columna">
    <h3 class="dashboard-subtitulo">Alertas del Sistema</h3>
    
    <div class="contenedor-alertas" id="contenedor-alertas-siger">
        
        <x-alertas.notificacion tipo="peligro" titulo="Entrega Retrasada">
            El <strong>Computador Dell Inspiron</strong> debió devolverse a las 10:00 AM.
        </x-alertas.notificacion>

        <x-alertas.notificacion tipo="advertencia" titulo="Mantenimiento">
            El Aula 101 reporta fallas en la red.
        </x-alertas.notificacion>

    </div>
    </div>

</div>

{{--- 3. SECCIÓN INFERIOR: PENDIENTES ---}}
<div class="dashboard-pendientes-seccion">
    <div class="pendientes-header">
        <h3 class="dashboard-subtitulo">
            <i class="fas fa-clock"></i> Solicitudes Pendientes
        </h3>
        <a href="{{ url('/reservas/gestion') }}" class="btn-ver-todo">Ver toda la gestión</a>
    </div>

    <div class="container-tarjetas-vertical">
        @foreach($reservasSimuladas as $reserva)
            @if(strtolower($reserva->estado) === 'pendiente')
                <div class="tarjeta-dashboard-link">
                    @component('components.tarjetas.tarjeta-reserva', [
                        'id'          => $reserva->id,
                        'foto'        => asset('storage/images/activos/default.jpeg'),
                        'nombre'      => $reserva->recurso_nombre,
                        'estado'      => $reserva->estado,
                        'solicitante' => $reserva->usuario_nombre,
                        'fecha'       => \Carbon\Carbon::parse($reserva->fecha)->format('d \d\e F Y'),
                        'horaInicio'  => $reserva->hora_inicio,
                        'horaFin'     => $reserva->hora_fin,
                        'ubicacion'   => $reserva->ubicacion,
                        'urlDetalles' => url('/reservas/gestion')
                    ])
                    @endcomponent
                </div>
            @endif
        @endforeach
    </div>
</div>

<x-reservas.modal-detalle-reserva :esAdmin="$esAdmin" />

@endsection