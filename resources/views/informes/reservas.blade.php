@extends('layouts.app')

@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'true')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/components/tarjeta-reserva.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/pages/reporte-reservas.css') }}?v={{ time() }}">
@endpush

@php
    $user = auth()->user();
    $userId  = $user->id ?? 1;
    $rolSlug = strtolower($user->role->slug ?? $user->rol->slug ?? $user->role ?? $user->rol ?? '');
    $rolId   = $user->role_id ?? $user->rol_id ?? null;
    $esSecretaria = ($rolSlug === 'secretaria' || $rolId == 1);
    $urlRegresar = '#';
@endphp

@section('rutaRegresar', $urlRegresar)
@section('mostrarPerfil', 'true')

@section('content')

<div class="siger-modulo-reporte">
    
    <div class="reporte-encabezado">
        <div>
            <h2 class="reporte-titulo">Informe Histórico de Reservas</h2>
            <p class="reporte-subtitulo">Consolidado institucional de solicitudes y préstamos de recursos</p>
        </div>
        
        <x-botones.boton type="button" class="btn btn-siger-imprimir" onclick="window.print()">
            <i class="fas fa-file-excel me-2"></i> Exportar a Excel
        </x-botones.boton>
    </div>

    <x-filtros.filtro-fecha-estado action="#" />

    <div class="reporte-contenedor-principal">
        <div class="reporte-barra-info">
            <span class="reporte-conteo-registros">
                Registros Encaminados ({{ count($reservas ?? []) }})
            </span>
            <span class="reporte-filtro-etiqueta">Filtro aplicado</span>
        </div>

        <div class="container-tarjetas-vertical">
            @forelse($reservas ?? [] as $reserva)
                <div class="tarjeta-wrapper recurso-item mb-3">
                    @component('components.tarjetas.tarjeta-reserva', [
                        'id'          => $reserva->id,
                        'foto'        => asset('storage/images/activos/default.jpeg'),
                        'nombre'      => $reserva->recurso_nombre,
                        'estado'      => $reserva->estado,
                        'solicitante' => $reserva->usuario_nombre,
                        'fecha'       => \Carbon\Carbon::parse($reserva->fecha_inicio)->format('d/m/Y'),
                        'horaInicio'  => $reserva->hora_inicio,
                        'horaFin'     => $reserva->hora_fin,
                        'ubicacion'   => $reserva->ubicacion,
                        'urlGestion'  => '#',
                        'esMultiple'  => $reserva->es_multiple ?? false,
                        'recursos'    => $reserva->recursos_lista ?? []
                    ])
                    @endcomponent
                </div>
            @empty
                <div class="reporte-vacio text-center py-5">
                    <i class="fas fa-folder-open fa-3x mb-3 icono-vacio"></i>
                    <h5 class="titulo-vacio">Sin registros para mostrar</h5>
                    <p class="subtitulo-vacio">No se encontraron reservas que coincidan con los criterios del filtro.</p>
                </div>
            @endforelse
        </div>
    </div>

    <x-modal id="modalDetalleReserva" title="Detalle de la Reserva" size="lg">
        <div id="contenidoModalDetalleReserva">
            <x-reservas.resumen-reserva :reserva="null" />
        </div>
    </x-modal>

</div>

@endsection