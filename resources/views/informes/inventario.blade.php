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
    ];

    // Datos de prueba para la pestaña 2 (Aulas) - Se agregaron las columnas 'Tipo' y 'Último Mantenimiento'
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

{{-- Estilos personalizados y Media Queries explicados --}}
<style>
    /* ESTOS SON LOS MEDIA QUERIES Y REGLAS RESPONSIVAS:
       Sirven para adaptar el diseño en dispositivos móviles y pantallas pequeñas. */

    /* Contenedor flexible para alinear el título y el botón de Excel horizontalmente */
    .encabezado-tabla-acciones {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        width: 100%;
        gap: 1rem;
    }

    /* Estilo estilizado para el botón de Exportar a Excel */
    .btn-exportar-excel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background-color: #107c41; /* Verde corporativo de Excel */
        color: #ffffff !important;
        padding: 9px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.88rem;
        white-space: nowrap;
        border: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        transition: background-color 0.2s ease, transform 0.1s ease;
    }

    .btn-exportar-excel:hover {
        background-color: #0b5c30;
        transform: translateY(-1px);
    }

    /* MEDIA QUERY: Pantallas menores o iguales a 768px (Tablets y celulares) */
    @media (max-width: 768px) {
        /* Cambia las pestañas superiores a disposición vertical en pantallas pequeñas */
        .tabs-gestion-admin {
            flex-direction: column;
            gap: 0.5rem !important;
        }

        /* Cambia el encabezado para que el botón de Excel baje y se adapte al ancho total */
        .encabezado-tabla-acciones {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-exportar-excel {
            width: 100%; /* El botón ocupa todo el ancho en móviles */
        }

        .seccion-tab {
            padding: 1rem !important; /* Reduce el relleno interno en celulares */
        }
    }
</style>

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
            
            {{-- Encabezado de la tabla con el botón alineado limpiamente a la derecha --}}
            <div class="encabezado-tabla-acciones">
                <h3 style="margin: 0; color: var(--color-principal);">Inventario de activos</h3>
                <a href="#" class="btn-exportar-excel">
                    <i class="fas fa-file-excel"></i> Exportar a Excel
                </a>
            </div>

            @include('components.tablas.tabla-informe', [
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
            
            {{-- Encabezado de la tabla con el botón alineado limpiamente a la derecha --}}
            <div class="encabezado-tabla-acciones">
                <h3 style="margin: 0; color: var(--color-principal);">Informe de aulas</h3>
                <a href="#" class="btn-exportar-excel">
                    <i class="fas fa-file-excel"></i> Exportar a Excel
                </a>
            </div>

            {{-- Componente de la tabla con las 2 nuevas columnas agregadas ('tipo' y 'ultimo_mantenimiento') --}}
            @include('components.tablas.tabla-informe', [
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