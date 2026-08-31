@extends('layouts.app')

@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'false')
@section('mostrarPerfil', 'false')

@section('content')

@php
    // Datos de prueba para la pestaña 1 (Activos)
    $activos = [
        [
            'nombre_activo' => 'Laptop Dell XPS 13',
            'serial' => 'SN123456789',
            'marca' => 'Dell',
            'categoria' => 'Computadora portátil',
            'estado' => 'Disponible',
            'ubicacion' => 'Oficina Principal',
            'anio_adquisicion' => 2022,
            'precio' => '$1,200.00'
        ],
        [
            'nombre_activo' => 'Proyector Epson X500',
            'serial' => 'SN987654321',
            'marca' => 'Epson',
            'categoria' => 'Proyector',
            'estado' => 'En uso',
            'ubicacion' => 'Sala de Conferencias',
            'anio_adquisicion' => 2021,
            'precio' => '$800.00'
        ],
        [
            'nombre_activo' => 'Laptop Dell XPS 13',
            'serial' => 'SN123456789',
            'marca' => 'Dell',
            'categoria' => 'Computadora portátil',
            'estado' => 'Disponible',
            'ubicacion' => 'Oficina Principal',
            'anio_adquisicion' => 2022,
            'precio' => '$1,200.00'
        ],
    ];

    // Datos de prueba para la pestaña 2 (Aulas)
    $aulas = [
        [
            'nombre_aula' => 'Aula 101 - Sistemas',
            'tipo' => 'Laboratorio de Cómputo',
            'capacidad' => '30 personas',
            'reservable' => 'Sí',
            'estado' => 'Disponible',
            'ultimo_mantenimiento' => '15/07/2026'
        ],
        [
            'nombre_aula' => 'Auditorio Principal',
            'tipo' => 'Magno / Eventos',
            'capacidad' => '150 personas',
            'reservable' => 'Sí',
            'estado' => 'Bueno',
            'ultimo_mantenimiento' => '02/08/2026'
        ],
    ];
@endphp

<link rel="stylesheet" href="{{ asset('css/components/botones.css') }}">  
<link rel="stylesheet" href="{{ asset('css/components/form-usuario.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/informes-inventario.css') }}">

<h2 class="titulo-pagina"><i class="fas fa-file-alt"></i> Informes de Inventario</h2>

<div class="tarjeta-blanca-datos">
        
    {{-- Navegación de pestañas interna --}}
    <div class="tabs-gestion-admin">
        <button type="button" class="tab-btn activo" onclick="cambiarTab(event, 'contenido-activos')">
            Activos
        </button>
        <button type="button" class="tab-btn" onclick="cambiarTab(event, 'contenido-aulas')">
            Aulas
        </button>
    </div>

    <div class="modulo-placeholder">
        
        {{-- Pestaña 1: Inventario de activos --}}
        <div id="contenido-activos" class="seccion-tab activa">
            @include('components.tablas.tabla-informe', [
                'titulo' => 'Informe de activos',
                'columnas' => [
                    ['titulo' => 'Nombre del activo', 'campo' => 'nombre_activo'],
                    ['titulo' => 'Serial', 'campo' => 'serial'],
                    ['titulo' => 'Ubicación', 'campo' => 'ubicacion'],
                    ['titulo' => 'Marca', 'campo' => 'marca'],
                    ['titulo' => 'Categoría', 'campo' => 'categoria'],
                    ['titulo' => 'Estado', 'campo' => 'estado'],
                    ['titulo' => 'Año de adquisición', 'campo' => 'anio_adquisicion'],
                    ['titulo' => 'Precio', 'campo' => 'precio'],
                ],
                'datos' => $activos ?? []
            ])
        </div>

        {{-- Pestaña 2: Informe de aulas --}}
        <div id="contenido-aulas" class="seccion-tab">
            @include('components.tablas.tabla-informe', [
                'titulo' => 'Informe de aulas',
                'columnas' => [
                    ['titulo' => 'Nombre del aula', 'campo' => 'nombre_aula'],
                    ['titulo' => 'Tipo de aula', 'campo' => 'tipo'],
                    ['titulo' => 'Capacidad', 'campo' => 'capacidad'],
                    ['titulo' => 'Reservable', 'campo' => 'reservable'],
                    ['titulo' => 'Estado', 'campo' => 'estado'],
                    ['titulo' => 'Último Mantenimiento', 'campo' => 'ultimo_mantenimiento'],
                ],
                'datos' => $aulas ?? []
            ])
        </div>

    </div>

</div>

{{-- Script JS encargado de alternar el estado visible/oculto de cada pestaña --}}
<script>
    function cambiarTab(evt, tabId) {
        // 1. Ocultar todas las secciones
        const secciones = document.querySelectorAll('.seccion-tab');
        secciones.forEach(sec => sec.classList.remove('activa'));

        // 2. Desactivar el estado activo visual de los botones
        const botones = document.querySelectorAll('.tab-btn');
        botones.forEach(btn => btn.classList.remove('activo'));

        // 3. Mostrar la pestaña elegida y aplicar clase activa al botón
        document.getElementById(tabId).classList.add('activa');
        evt.currentTarget.classList.add('activo');
    }
</script>

@endsection