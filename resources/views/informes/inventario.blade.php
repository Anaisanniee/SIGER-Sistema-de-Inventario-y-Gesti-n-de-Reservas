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

<h2 class="titulo-pagina"><i class="fas fa-file-alt"></i> Informes de Inventario</h2>

<div class="tarjeta-blanca-datos">
        
    {{-- Navegación de pestañas interna --}}
    <div class="tabs-gestion-admin" style="display: flex; gap: 1rem; border-bottom: 2px solid #e2e8f0; margin-bottom: 1.5rem;">
        <button type="button" class="tab-btn activo" onclick="cambiarTab(event, 'contenido-activos')" style="padding: 1rem; border: none; background: none; font-weight: bold; cursor: pointer; border-bottom: 3px solid var(--color-principal); color: var(--color-texto);">
            Inventario de activos
        </button>
        <button type="button" class="tab-btn" onclick="cambiarTab(event, 'contenido-aulas')" style="padding: 1rem; border: none; background: none; font-weight: bold; cursor: pointer; border-bottom: 3px solid transparent; color: var(--color-azulado);">
            Informe de aulas
        </button>
    </div>

    <div class="modulo-placeholder" style="display: block; padding: 1rem; width: 100%;">
        
        {{-- Pestaña 1: Inventario de activos --}}
        <div id="contenido-activos" class="seccion-tab" style="background: var(--color-fondo); padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); width: 100%; display: block; text-align: left;">
            @include('components.tablas.tabla-informe', [
                'titulo' => 'Inventario de activos',
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
        <div id="contenido-aulas" class="seccion-tab" style="background: var(--color-fondo); padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); width: 100%; display: none; text-align: left;">
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
        // 1. Ocultar todos los contenedores de secciones
        const secciones = document.querySelectorAll('.seccion-tab');
        secciones.forEach(sec => sec.style.display = 'none');

        // 2. Desactivar el estado activo visual de los botones de pestañas
        const botones = document.querySelectorAll('.tab-btn');
        botones.forEach(btn => {
            btn.style.borderBottomColor = 'transparent';
            btn.style.color = 'var(--color-azulado)';
        });

        // 3. Mostrar la pestaña elegida y aplicar el borde activo
        document.getElementById(tabId).style.display = 'block';
        evt.currentTarget.style.borderBottomColor = 'var(--color-principal)';
        evt.currentTarget.style.color = 'var(--color-texto)';
    }
</script>

@endsection