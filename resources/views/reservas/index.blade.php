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
            <h2 class="titulo-pagina"><i class="fas fa-calendar-alt"></i> Mis Solicitudes y Reservas</h2>
            <p class="subtitulo-pagina">Consulta el estado detallado y el resumen completo de tus recursos solicitados.</p>
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

        <!-- COLUMNA DERECHA: AGENDA SIEMPRE VISIBLE -->
        <div class="columna-agenda-permanente">
            <div class="card-calendario-fijo">
                <div class="agenda-header-siger">
                    <h4><i class="fas fa-calendar-day"></i> Vista de Ocupación</h4>
                    <span class="badge-agenda-hoy">Hoy</span>
                </div>
                <div class="placeholder-agenda-render">
                    <i class="fas fa-clock" style="font-size: 2rem; color: var(--color-borde); margin-bottom: 12px;"></i>
                    <p>Espacio reservado para el calendario interactivo.</p>
                    <small style="color: var(--color-texto-secundario);">Permitirá cruzar horarios visualmente de forma inmediata.</small>
                </div>
            </div>
        </div>

    </div>

    <!-- MODAL GENERAL DE RESUMEN (PLANTILLA CENTRAL) -->
    <x-modal id="modalgeneral" titulo="Detalle de la Reserva" subtitulo="Estado de la Solicitud">
        
        <x-reservas.resumen-reserva :mostrarSubtitulo="false" />

        <!-- ACCIONES DE GESTIÓN (VISIBILIDAD CONTROLADA POR JS DESDE LA TARJETA) -->
        @if($esAdmin)
            <div id="contenedor-acciones-modal" class="d-flex justify-content-between align-items-center w-100 mt-4 pt-3 border-top">
                <!-- Bloque Pendiente -->
                <div id="bloque-acciones-pendiente" class="d-flex gap-2 w-100 justify-content-between">
                    <x-botones.boton type="button" class="btn btn-rojo" data-bs-toggle="modal" data-bs-target="#modalConfirmarRechazo">
                         Rechazar
                    </x-botones.boton>
                    <x-botones.boton type="button" class="btn">
                         Aprobar Solicitud
                    </x-botones.boton>
                </div>

                <!-- Bloque Revertir -->
                <div id="bloque-acciones-revertir" class="d-none gap-2 w-100 justify-content-end">
                    <x-botones.boton type="button" class="btn btn-amarillo">
                        Revertir a Pendiente
                    </x-botones.boton>
                </div>
            </div>
        @endif
    </x-modal>

    <!-- MODAL SECUNDARIO DE CONFIRMACIÓN DE RECHAZO -->
    @if($esAdmin)
        <x-modal id="modalConfirmarRechazo" titulo="¿Rechazar solicitud?" subtitulo="Por favor indique la razón para notificar al usuario">
            <form id="formRechazarReserva" action="#" method="POST" onsubmit="event.preventDefault();">
                @csrf
                <div class="form-group-siger mt-2">
                    <label for="motivo_rechazo" class="fw-bold mb-1">Motivo del Rechazo <span class="text-danger">*</span></label>
                    <textarea id="motivo_rechazo" name="motivo_rechazo" class="form-control" rows="3" required placeholder="Escriba la razón del rechazo..."></textarea>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4 pt-2 border-top">
                    <x-botones.boton type="button" class="btn btn-rojo" data-bs-dismiss="modal">
                        Cancelar
                    </x-botones.boton>
                    <x-botones.boton type="submit" class="btn" data-bs-dismiss="modal">
                        Confirmar Rechazo
                    </x-botones.boton>
                </div>
            </form>
        </x-modal>
    @endif

</div>

<!-- SCRIPT DE CARGA DINÁMICA DEL MODAL -->
<script>
    function cargarDatosModal(datos) {
        // 1. Asignar los valores recibidos a los elementos del modal
        const tituloEl = document.getElementById('modalgeneral-titulo');
        if (tituloEl) tituloEl.innerText = datos.titulo || 'Detalle de Reserva';

        // 2. Evaluar y conmutar la visibilidad de las acciones de gestión
        const bloquePendiente = document.getElementById('bloque-acciones-pendiente');
        const bloqueRevertir = document.getElementById('bloque-acciones-revertir');

        if (bloquePendiente && bloqueRevertir) {
            const estado = (datos.estado || '').toLowerCase().trim();

            // Resetear ambos bloques
            bloquePendiente.classList.add('d-none');
            bloquePendiente.classList.remove('d-flex');
            bloqueRevertir.classList.add('d-none');
            bloqueRevertir.classList.remove('d-flex');

            // Mostrar el bloque según el estado recibido en el JSON
            if (estado === 'pendiente') {
                bloquePendiente.classList.remove('d-none');
                bloquePendiente.classList.add('d-flex');
            } else if (estado === 'aprobada' || estado === 'rechazada') {
                bloqueRevertir.classList.remove('d-none');
                bloqueRevertir.classList.add('d-flex');
            }
        }
    }
</script>

<script src="{{ asset('js/componentes/filtros-inventario.js') }}"></script>
@endsection