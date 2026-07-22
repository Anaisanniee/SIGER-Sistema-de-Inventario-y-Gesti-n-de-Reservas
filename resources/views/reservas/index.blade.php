@extends('layouts.app')

@section('mostrarBusqueda', 'true')
@section('mostrarRegresar', 'true')

@section('content')
<link rel="stylesheet" href="{{ asset('css/pages/reservas.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/tarjeta-reserva.css') }}">

@php
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
            'estado' => 'aprobada',
            'usuario_nombre' => 'Docente María Alejandra',
            'fecha' => '2026-07-13',
            'hora_inicio' => '10:00 AM',
            'hora_fin' => '12:00 PM',
            'ubicacion' => 'Laboratorio de Sistemas'
        ],
        (object)[
            'id' => 3,
            'recurso_foto' => null,
            'recurso_nombre' => 'Auditorio Central',
            'estado' => 'rechazada',
            'usuario_nombre' => 'Ing. Ricardo Torres',
            'fecha' => '2026-07-15',
            'hora_inicio' => '02:00 PM',
            'hora_fin' => '06:00 PM',
            'ubicacion' => 'Bloque B'
        ]
    ];
@endphp

<div class="panel-administracion-contenedor">
    
    <!-- CABECERA DEL PANEL -->
    <div class="cabecera-panel">
        <div class="texto-cabecera">
            <h2 class="titulo-pagina"><i class="fas fa-calendar-alt"></i> Solicitudes de equipos</h2>
            <p class="subtitulo-pagina">Revisa, aprueba o rechaza las solicitudes institucionales con soporte de agenda en tiempo real.</p>
        </div>
        
        <div class="acciones-rapidas-panel">
            <x-botones.boton clase="btn-papelera" url="{{ url('/reservas/historial') }}">
                <i class="fas fa-history"></i> Historial
            </x-botones.boton>
        </div>
    </div>

    <!-- BLOQUE DE MÉTRICAS / KPIs -->
    <div class="contenedor-kpis">
        @component('components.filtros.kpi-selector', [
            'kpis' => [
                ['filtro' => 'pendiente',  'color' => 'amarillo','icono' => 'fas fa-clock',       'titulo' => 'Pendientes',  'subtitulo' => 'Por revisar'],
                ['filtro' => 'aprobada',   'color' => 'verde',  'icono' => 'fas fa-check-circle','titulo' => 'Aprobadas',   'subtitulo' => 'Listas'],
                ['filtro' => 'rechazada',  'color' => 'rojo',   'icono' => 'fas fa-times-circle','titulo' => 'Rechazadas',  'subtitulo' => 'Denegadas']
            ]
        ])
        @endcomponent
    </div>

    <!-- DISTRIBUCIÓN EN DOS COLUMNAS -->
    <div class="dashboard-reservas-grid">
        
        <!-- COLUMNA IZQUIERDA: LISTADO DE TARJETAS IDÉNTICAS -->
        <div class="columna-solicitudes">
            <div class="container-tarjetas-vertical">
                @foreach($reservasSimuladas as $reserva)
                    @php
                        $tagsReserva = ['reserva', strtolower($reserva->estado)];
                        $strTagsReserva = implode(' ', $tagsReserva);
                    @endphp
                    
                    <div class="tarjeta-wrapper recurso-item" data-tags="{{ $strTagsReserva }}">
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
                            'urlDetalles' => '#'
                        ])
                        @endcomponent
                    </div>
                @endforeach
            </div>
        </div>

        <!-- COLUMNA DERECHA: AGENDA SIEMPRE VISIBLE -->
        <div class="columna-agenda-permanente">
            <div class="card-calendario-fijo">
                <div class="agenda-header-siger">
                    <h4><i class="fas fa-calendar-day"></i> Vista de Ocupación</h4>
                    <span class="badge-agenda-hoy">Hoy</span>
                </div>
                <!-- Contenedor donde renderizarás el calendario/agenda luego -->
                <div class="placeholder-agenda-render">
                    <i class="fas fa-clock" style="font-size: 2rem; color: var(--color-borde); margin-bottom: 12px;"></i>
                    <p>Espacio reservado para el calendario interactivo.</p>
                    <small style="color: var(--color-texto-secundario);">Permitirá cruzar horarios visualmente de forma inmediata.</small>
                </div>
            </div>
        </div>

    </div>

    {{--- MODAL DE RECHAZO ---}}
    <x-modal id="modalConfirmarRechazo" titulo="¿Rechazar solicitud?">
        <form id="formRechazarReserva" action="#" method="POST" onsubmit="event.preventDefault();">
            @csrf
            <div class="form-group-siger">
                <label for="motivo_rechazo">Motivo del Rechazo <span style="color: var(--color-rojo);">*</span></label>
                <textarea id="motivo_rechazo" name="motivo_rechazo" class="form-control" required placeholder="Escriba la razón del rechazo..."></textarea>
            </div>
            <div class="modal-botones-acciones">
                <x-botones.boton type="button" clase="btn-verde" data-bs-dismiss="modal">Regresar</x-botones.boton>
                <x-botones.boton type="submit" clase="btn-rojo" data-bs-dismiss="modal">Confirmar Rechazo</x-botones.boton>
            </div>
        </form>
    </x-modal>
</div>

<script src="{{ asset('js/componentes/filtros-inventario.js') }}"></script>
@endsection