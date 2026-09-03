@extends('layouts.app')

@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'false')
@section('mostrarPerfil', 'false')

@section('content')

<link rel="stylesheet" href="{{ asset('css/components/botones.css') }}">  
<link rel="stylesheet" href="{{ asset('css/components/form-usuario.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/informes-inventario.css') }}">

<h2 class="titulo-pagina"><i class="fas fa-file-alt"></i> Informes de Inventario</h2>
<div class="tarjeta-blanca-datos">
        
    {{-- Cabecera con Pestañas y el Botón Único de Exportar --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div class="tabs-gestion-admin" style="margin-bottom: 0;">
            <button type="button" class="tab-btn activo" data-tab="contenido-activos">
                Activos
            </button>
            <button type="button" class="tab-btn" data-tab="contenido-aulas">
                Aulas
            </button>
        </div>

        {{-- ÚNICO BOTÓN GLOBAL DE EXPORTAR --}}
        <div>
            <a href="{{ route('informes.inventario.exportar', 'activos') }}" id="btnExportar" class="btn-exportar-excel">
                <i class="fas fa-file-excel"></i> Exportar a Excel
            </a>
        </div>
    </div>

    <div class="modulo-placeholder">
        
        {{-- Pestaña 1: Inventario de activos --}}
        <div id="contenido-activos" class="seccion-tab activa">
            @include('components.tablas.tabla-informe', [
                'titulo' => 'Informe de activos',
                'mostrarBoton' => false, {{-- Ocultamos el botón interno de la tabla --}}
                'columnas' => [
                    ['titulo' => 'Nombre del activo', 'campo' => 'nombre_activo'],
                    ['titulo' => 'Serial', 'campo' => 'serial'],
                    ['titulo' => 'Ubicación', 'campo' => 'ubicacion'],
                    ['titulo' => 'Marca', 'campo' => 'marca'],
                    ['titulo' => 'Categoría', 'campo' => 'categoria'],
                    ['titulo' => 'Estado', 'campo' => 'estado'],
                    ['titulo' => 'Año de adquisición', 'campo' => 'anio_adquisicion'],
                    ['titulo' => 'Precio', 'campo' => 'his_pre_valor'],
                ],
                'datos' => $activos ?? []
            ])
        </div>

        {{-- Pestaña 2: Informe de aulas --}}
        <div id="contenido-aulas" class="seccion-tab">
            @include('components.tablas.tabla-informe', [
                'titulo' => 'Informe de aulas',
                'mostrarBoton' => false, {{-- Ocultamos el botón interno de la tabla --}}
                'columnas' => [
                    ['titulo' => 'Nombre del aula', 'campo' => 'nombre_aula'],
                    ['titulo' => 'Tipo de aula', 'campo' => 'tip_aula_id'],
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const botonesTabs = document.querySelectorAll('.tab-btn');
        const secciones = document.querySelectorAll('.seccion-tab');
        const btnExportar = document.getElementById('btnExportar');

        const urlActivos = "{{ route('informes.inventario.exportar', 'activos') }}";
        const urlAulas = "{{ route('informes.inventario.exportar', 'aulas') }}";

        botonesTabs.forEach((boton, index) => {
            boton.addEventListener('click', function () {
                botonesTabs.forEach(btn => btn.classList.remove('activo'));
                secciones.forEach(sec => sec.classList.remove('activa'));

                this.classList.add('activo');

                if (index === 0) {
                    document.getElementById('contenido-activos').classList.add('activa');
                    if (btnExportar) {
                        btnExportar.href = urlActivos;
                    }
                } else if (index === 1) {
                    document.getElementById('contenido-aulas').classList.add('activa');
                    if (btnExportar) {
                        btnExportar.href = urlAulas;
                    }
                }
            });
        });
    });
</script>

@endsection