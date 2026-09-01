@extends('layouts.app')

@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'true')

@php
    $user = auth()->user();
    
    // Verificación segura de rol y sesión iniciada
    $userId  = $user->id ?? 1;
    $rolSlug = strtolower($user->role->slug ?? $user->rol->slug ?? $user->role ?? $user->rol ?? '');
    $rolId   = $user->role_id ?? $user->rol_id ?? null;

    // Acceso para Secretaría (1) y Rectora (2)
    $esSecretaria = ($rolSlug === 'secretaria' || $rolId == 1);
    
    // Mantendremos una URL neutra temporal para el botón regresar
    $urlRegresar = '#';
@endphp

@section('rutaRegresar', $urlRegresar)
@section('mostrarPerfil', 'true')

@section('content')

{{-- Hojas de estilo --}}
<link rel="stylesheet" href="{{ asset('css/components/tarjeta-reserva.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/reporte-reservas.css') }}">

<div class="siger-modulo-reporte">
    
    {{-- Encabezado del informe --}}
    <div class="reporte-encabezado">
        <div>
            <h2 class="reporte-titulo">
                Informe Histórico de Reservas
            </h2>
            <p class="reporte-subtitulo">
                Consolidado institucional de solicitudes y préstamos de recursos
            </p>
        </div>
        
        <x-botones.boton type="button" class="btn btn-siger-imprimir" onclick="window.print()">
            <i class="fas fa-file-excel me-2"></i> Exportar a Excel
        </x-botones.boton>
    </div>

    {{-- Componente reutilizable de filtro (fechas y estado) --}}
    <x-filtros.filtro-fecha-estado action="#" />

    {{-- Contenedor principal de tarjetas de reserva --}}
    <div class="reporte-contenedor-principal">
        
        <div class="reporte-barra-info">
            <span class="reporte-conteo-registros">
                Registros Encaminados ({{ count($reservas ?? [1, 2]) }})
            </span>
            <span class="reporte-filtro-etiqueta">
                Filtro aplicado
            </span>
        </div>

        <div class="container-tarjetas-vertical">
            @forelse($reservas ?? [
                (object)[
                    'id' => 201,
                    'recurso_nombre' => 'Aula de Informática 02',
                    'estado' => 'aprobada',
                    'usuario_nombre' => 'Carlos Mendoza (Docente)',
                    'fecha_inicio' => '2026-08-31',
                    'hora_inicio' => '07:00 AM',
                    'hora_fin' => '09:00 AM',
                    'ubicacion' => 'Bloque B - Piso 2',
                    'es_multiple' => false,
                    'recursos_lista' => []
                ],
                (object)[
                    'id' => 202,
                    'recurso_nombre' => 'Kit Televisor + Videobeam',
                    'estado' => 'pendiente',
                    'usuario_nombre' => 'Ana López (Docente)',
                    'fecha_inicio' => '2026-09-02',
                    'hora_inicio' => '10:00 AM',
                    'hora_fin' => '12:00 PM',
                    'ubicacion' => 'Auditorio Principal',
                    'es_multiple' => true,
                    'recursos_lista' => ['Televisor Samsung 55"', 'Proyector Epson']
                ]
            ] as $reserva)

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