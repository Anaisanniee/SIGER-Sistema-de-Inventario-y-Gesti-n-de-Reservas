@extends('layouts.app')
@section('mostrarBusqueda', 'true')
@php
    $user = auth()->user();
    $userId  = $user->id ?? $user->usu_id ?? 1;
    
    $rolSlug = strtolower(optional($user->role)->slug ?? optional($user->rol)->slug ?? $user->role ?? $user->rol ?? '');
    $nombreRol = strtolower(optional($user->role)->name ?? optional($user->rol)->name ?? optional($user->rol)->nombre ?? '');
    $rolId   = $user->role_id ?? $user->rol_id ?? null;

    if ($rolSlug === 'secretaria' || $nombreRol === 'secretaria' || $rolId == 1) {
        $urlRegresar = route('dashboard.secretaria', ['id' => $userId]);
    } elseif ($rolSlug === 'rectora' || $nombreRol === 'rectora' || $rolId == 2) {
        $urlRegresar = route('dashboard.rectora', ['id' => $userId]);
    }
@endphp
@section('rutaRegresar', $urlRegresar)
@section('mostrarRegresar', 'true')
@section('mostrarPerfil', 'true')

@section('content')

<link rel="stylesheet" href="{{ asset('css/components/botones.css') }}">  
<link rel="stylesheet" href="{{ asset('css/components/form-usuario.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/informes-inventario.css') }}">

<h2 class="titulo-pagina"><i class="fas fa-file-alt"></i> Informes de Inventario</h2>
<div class="tarjeta-blanca-datos">
        
    {{-- Cabecera con Pestañas y Botón Único de Exportar --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div class="tabs-gestion-admin" style="margin-bottom: 0;">
            <button type="button" class="tab-btn activo" data-tab="contenido-activos">
                Activos
            </button>
            <button type="button" class="tab-btn" data-tab="contenido-aulas">
                Aulas
            </button>
        </div>

        <div>
            <a href="{{ route('informes.inventario.exportar', 'activos') }}" id="btnExportar" class="btn-exportar-excel">
                <i class="fas fa-file-excel"></i> Exportar a Excel
            </a>
        </div>
    </div>

    @php
        // Formatear los datos de activos creando el botón con x-botones.boton
        $activosFormateados = collect($activos ?? [])->map(function ($activo) {
            $act = (array) $activo;
            $idActivo = $act['id'] ?? $act['ACT_ID'] ?? 1;

            $act['btn_historial'] = Blade::render('
                <x-botones.boton target="modalHistorial'.$idActivo.'" class="btn btn-siger-accion">
                    <i class="fas fa-eye" style="margin-right: 5px";></i> Ver Historial
                </x-botones.boton>
            ', ['idActivo' => $idActivo]);

            return $act;
        })->toArray();
    @endphp

    <div class="modulo-placeholder">
        
        {{-- Pestaña 1: Inventario de activos --}}
        <div id="contenido-activos" class="seccion-tab activa">
            @include('components.tablas.tabla-informe', [
                'titulo' => 'Informe de activos',
                'mostrarBoton' => false,
                'columnas' => [
                    ['titulo' => 'Nombre del activo', 'campo' => 'nombre_activo'],
                    ['titulo' => 'Serial', 'campo' => 'serial'],
                    ['titulo' => 'Ubicación', 'campo' => 'ubicacion'],
                    ['titulo' => 'Marca', 'campo' => 'marca'],
                    ['titulo' => 'Categoría', 'campo' => 'categoria'],
                    ['titulo' => 'Estado', 'campo' => 'estado'],
                    ['titulo' => 'Año de adquisición', 'campo' => 'anio_adquisicion'],
                    ['titulo' => 'Precio', 'campo' => 'his_pre_valor'],
                    ['titulo' => 'Historial de precio', 'campo' => 'btn_historial', 'html' => true],
                ],
                'datos' => $activosFormateados
            ])
        </div>

        {{-- Pestaña 2: Informe de aulas --}}
        <div id="contenido-aulas" class="seccion-tab">
            @include('components.tablas.tabla-informe', [
                'titulo' => 'Informe de aulas',
                'mostrarBoton' => false,
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

{{-- MODALES CON EL COMPONENTE TABLA-INFORME ADENTRO --}}
@foreach($activos ?? [] as $activo)
    @php
        $act = (array) $activo;
        $idActivo = $act['id'] ?? $act['ACT_ID'] ?? 1;
        $nombreActivo = $act['nombre_activo'] ?? $act['ACT_NOMBRE'] ?? 'Activo';
        $serial = $act['serial'] ?? $act['ACT_SERIAL'] ?? 'Sin Serial';
        
        $historialData = collect($act['historialPrecios'] ?? $act['historial_precios'] ?? [])->map(function($h) {
            $item = (array) $h;
            $valorNum = $item['HIS_PRE_VALOR'] ?? $item['valor'] ?? 0;
            return [
                'fecha'  => $item['HIS_PRE_FECHA_CAMBIO'] ?? $item['fecha'] ?? 'N/A',
                'motivo' => $item['HIS_PRE_MOTIVO'] ?? $item['motivo'] ?? 'Sin especificación',
                'valor'  => '$' . number_format((float)$valorNum, 2),
            ];
        })->toArray();
    @endphp

    <x-modal 
        id="modalHistorial{{ $idActivo }}" 
        titulo="Historial de Precios" 
        subtitulo="{{ $nombreActivo }} — Serial: {{ $serial }}"
    >
        {{-- Llamado a la tabla informe dentro del modal --}}
        @include('components.tablas.tabla-informe', [
            'titulo' => 'Registros del Historial',
            'mostrarBoton' => false,
            'columnas' => [
                ['titulo' => 'Fecha de Cambio', 'campo' => 'fecha'],
                ['titulo' => 'Motivo del Cambio', 'campo' => 'motivo'],
                ['titulo' => 'Valor Registrado', 'campo' => 'valor'],
            ],
            'datos' => $historialData
        ])
    </x-modal>
@endforeach

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
                    if (btnExportar) btnExportar.href = urlActivos;
                } else if (index === 1) {
                    document.getElementById('contenido-aulas').classList.add('activa');
                    if (btnExportar) btnExportar.href = urlAulas;
                }
            });
        });
    });
</script>

@endsection