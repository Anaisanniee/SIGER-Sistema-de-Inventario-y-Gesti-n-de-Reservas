@extends('layouts.app')

@section('mostrarBusqueda', 'true')
@section('mostrarRegresar', 'true')

@section('content')
<link rel="stylesheet" href="{{ asset('css/pages/reservas.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/tarjeta-reserva.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/resumen-reserva.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/detalle-recurso.css') }}">

@php
    $esAdmin = Auth::user()->esAdmin ?? true; 

    $reservasSimuladas = [
        (object)[
            'id' => 1,
            'recurso_foto' => null,
            'recurso_nombre' => 'Computador Dell Inspiron',
            'estado' => 'pendiente',
            'usuario_nombre' => 'Docente Carlos Mendoza',
            'identificacion' => '1.004.234.111',
            'email' => 'carlos.mendoza@colegio.edu.co',
            'motivo' => 'Clase de programación orientada a objetos.',
            'fecha_inicio' => '2026-07-12',
            'fecha_fin' => '2026-07-12',
            'hora_inicio' => '08:00 AM',
            'hora_fin' => '10:00 AM',
            'ubicacion' => 'Aula 101',
            'tipo_recurso' => 'activo',
            'es_multiple' => false,
            'recursos_lista' => []
        ],
        (object)[
            'id' => 2,
            'recurso_foto' => null,
            'recurso_nombre' => 'Videobeam Epson X41',
            'estado' => 'aprobada',
            'usuario_nombre' => 'Docente María Alejandra',
            'identificacion' => '1.004.234.222',
            'email' => 'maria.alejandra@colegio.edu.co',
            'motivo' => 'Presentación de proyectos finales de historia.',
            'fecha_inicio' => '2026-07-13',
            'fecha_fin' => '2026-07-13',
            'hora_inicio' => '10:00 AM',
            'hora_fin' => '12:00 PM',
            'ubicacion' => 'Laboratorio de Sistemas',
            'tipo_recurso' => 'activo',
            'es_multiple' => false,
            'recursos_lista' => []
        ],
        (object)[
            'id' => 3,
            'recurso_foto' => null,
            'recurso_nombre' => 'Auditorio Central',
            'estado' => 'rechazada',
            'usuario_nombre' => 'Ing. Ricardo Torres',
            'identificacion' => '1.004.234.333',
            'email' => 'ricardo.torres@colegio.edu.co',
            'motivo' => 'Conferencia institucional sobre desarrollo de software.',
            'fecha_inicio' => '2026-07-15',
            'fecha_fin' => '2026-07-15',
            'hora_inicio' => '02:00 PM',
            'hora_fin' => '06:00 PM',
            'ubicacion' => 'Bloque B',
            'tipo_recurso' => 'aula',
            'es_multiple' => false,
            'recursos_lista' => []
        ]
    ];
@endphp

<div class="panel-administracion-contenedor">
    
    <!-- CABECERA DEL PANEL -->
    <div class="cabecera-panel">
        <div class="texto-cabecera">
            <h2 class="titulo-pagina"><i class="fas fa-calendar-alt"></i> Solicitudes de Reservas</h2>
            <p class="subtitulo-pagina">Consulta el estado detallado y el resumen completo de los recursos solicitados.</p>
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
        
        <!-- COLUMNA IZQUIERDA: LISTADO DE TARJETAS -->
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
                            'fecha'       => \Carbon\Carbon::parse($reserva->fecha_inicio)->format('d \d\e F Y'),
                            'horaInicio'  => $reserva->hora_inicio,
                            'horaFin'     => $reserva->hora_fin,
                            'ubicacion'   => $reserva->ubicacion,
                            'urlGestion'  => '#',
                            'esMultiple'  => $reserva->es_multiple ?? false,
                            'recursos'    => $reserva->recursos_lista ?? []
                        ])
                        @endcomponent
                    </div>
                @endforeach
            </div>
        </div>

        <!-- COLUMNA DERECHA: AGENDA PERMANENTE -->
        <div class="columna-agenda-permanente">
        <x-agendas.agenda :eventos="[
                [
                    'title' => 'Computador Dell - Prof. Carlos',
                    'start' => '2026-08-12T08:00:00',
                    'end' => '2026-08-12T10:00:00',
                    'extendedProps' => [
                        'recurso' => 'Computador Dell Inspiron',
                        'usuario' => 'Carlos Mendoza (Docente)',
                        'estado' => 'Aprobado'
                    ]
                ],
                [
                    'title' => 'Reserva Aula 101',
                    'start' => '2026-08-15T10:00:00',
                    'end' => '2026-08-15T12:00:00',
                    'extendedProps' => [
                        'recurso' => 'Aula 101 (Audiovisuales)',
                        'usuario' => 'María Pérez (Docente)',
                        'estado' => 'Aprobado'
                    ]
                ]
            ]" />
        </div>

    </div>

    {{-- COMPONENTE DEL MODAL GENERAL CON SU SCRIPT INCLUIDO --}}
    <x-reservas.modal-detalle-reserva :esAdmin="$esAdmin" />

</div>

<script src="{{ asset('js/componentes/filtros-inventario.js') }}"></script>
@endsection