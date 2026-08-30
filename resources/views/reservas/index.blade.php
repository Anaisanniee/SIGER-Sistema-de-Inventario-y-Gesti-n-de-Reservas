@extends('layouts.app')

@section('mostrarBusqueda', 'true')
@section('rutaRegresar', route('dashboard.secretario'))

@section('content')
<link rel="stylesheet" href="{{ asset('css/pages/reservas.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/tarjeta-reserva.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/resumen-reserva.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/detalle-recurso.css') }}">

@php
    $esAdmin = Auth::user()->esAdmin ?? true; 
@endphp

<div class="panel-administracion-contenedor">
    
    <div class="cabecera-panel">
        <div class="texto-cabecera">
            <h2 class="titulo-pagina" style="color: var(--color-principal);"><i class="fas fa-calendar-alt"></i> Solicitudes de Reservas</h2>
            <p class="subtitulo-pagina">Consulta el estado detallado y el resumen completo de los recursos solicitados.</p>
        </div>
        
        <div class="acciones-rapidas-panel">
            <x-botones.boton clase="btn-papelera" url="{{ url('/reservas/historial') }}">
                <i class="fas fa-history" style="margin-right: 5px;"></i> Historial
            </x-botones.boton>
        </div>
    </div>

    <div class="contenedor-kpis">
        @component('components.filtros.kpi-selector', [
            'kpis' => [
                ['filtro' => 'pendiente',  'color' => 'amarillo','icono' => 'fas fa-clock',      'titulo' => 'Pendientes',  'subtitulo' => 'Por revisar'],
                ['filtro' => 'aprobada',   'color' => 'verde',  'icono' => 'fas fa-check-circle','titulo' => 'Aprobadas',   'subtitulo' => 'Listas'],
                ['filtro' => 'rechazada',  'color' => 'rojo',   'icono' => 'fas fa-times-circle','titulo' => 'Rechazadas',  'subtitulo' => 'Denegadas']
            ]
        ])
        @endcomponent
    </div>

    <div class="dashboard-reservas-grid">
        
        <div class="columna-solicitudes">
            <div class="container-tarjetas-vertical">

                {{-- FUNCIONES AUXILIARES Y DE MAPEO DE TARJETAS --}}
                @php
                    $generarHtmlTarjeta = function($reserva) {
                        $totalDetalles = $reserva->detalles->count();
                        $esMultiple = $totalDetalles > 1;
                        $primerDetalle = $reserva->detalles->first();

                        // Mapeo independiente por cada detalle priorizando el Activo si existe
                        $listaRecursosMultiples = $reserva->detalles->map(function($det) {
                            if (!empty($det->act_id)) {
                                $activoObj = $det->activo ?? \App\Models\ActivosModels::find($det->act_id);
                                if ($activoObj) {
                                    $rutaBdActivo = $activoObj->act_foto ?? $activoObj->foto ?? null;
                                    $fotoActivo = !empty($rutaBdActivo)
                                        ? (str_starts_with($rutaBdActivo, 'http') ? $rutaBdActivo : (str_starts_with($rutaBdActivo, 'storage/') ? asset($rutaBdActivo) : asset('storage/' . $rutaBdActivo)))
                                        : asset('storage/images/activos/default.jpeg');

                                    return (object)[
                                        'es_aula' => false,
                                        'nombre' => $activoObj->act_nombre ?? $activoObj->nombre ?? $activoObj->nombre_activo ?? 'Activo sin nombre',
                                        'serial' => $activoObj->act_serial ?? $activoObj->serial ?? $activoObj->codigo ?? 'N/A',
                                        'marca'  => $activoObj->act_marca ?? $activoObj->marca ?? 'N/A',
                                        'foto'   => $fotoActivo
                                    ];
                                }
                            }

                            if (!empty($det->aula_id)) {
                                $aulaObj = $det->aula ?? \App\Models\AulasModels::find($det->aula_id);
                                if ($aulaObj) {
                                    $rutaBdAula = $aulaObj->aula_foto ?? $aulaObj->foto ?? null;
                                    $fotoAula = !empty($rutaBdAula) 
                                        ? (str_starts_with($rutaBdAula, 'http') ? $rutaBdAula : (str_starts_with($rutaBdAula, 'storage/') ? asset($rutaBdAula) : asset('storage/' . $rutaBdAula)))
                                        : asset('storage/images/aulas/default.jpeg');

                                    $capacidad = $aulaObj->aula_capacidad ?? $aulaObj->capacidad ?? 'N/A';

                                    return (object)[
                                        'es_aula' => true,
                                        'nombre' => $aulaObj->aula_nombre ?? $aulaObj->nombre ?? 'Aula sin nombre',
                                        'serial' => $capacidad,
                                        'marca'  => 'Salón / Aula',
                                        'foto'   => $fotoAula
                                    ];
                                }
                            }

                            return (object)[
                                'es_aula' => false,
                                'nombre' => 'Recurso General',
                                'serial' => 'N/A',
                                'marca'  => 'N/A',
                                'foto'   => asset('storage/images/activos/default.jpeg')
                            ];
                        });

                        if ($esMultiple) {
                            $nombreRecurso = "Reserva Múltiple ({$totalDetalles} ítems)";
                        } else {
                            $nombreRecurso = $listaRecursosMultiples[0]->nombre ?? 'Recurso General';
                        }

                        $ubicacion = 'N/A';
                        if ($primerDetalle) {
                            if ($primerDetalle->aula) {
                                $ubicacion = $primerDetalle->aula->aula_nombre ?? $primerDetalle->aula->nombre ?? 'N/A';
                            } elseif (!empty($primerDetalle->aula_id)) {
                                $aulaUbicacion = \App\Models\AulasModels::find($primerDetalle->aula_id);
                                $ubicacion = $aulaUbicacion->aula_nombre ?? $aulaUbicacion->nombre ?? 'N/A';
                            }
                        }

                        $fotoPrincipal = $esMultiple ? asset('storage/activos/multiple-default.png') : ($listaRecursosMultiples[0]->foto ?? asset('storage/images/activos/default.jpeg'));
                        
                        // Extracción robusta de los datos del usuario / docente
                        $user = $reserva->usuario;
                        
                        // Extracción usando los campos reales de tu modelo User en mayúsculas
                        $primerNombre = $user->USU_PRIMER_NOMBRE ?? '';
                        $segundoNombre = $user->USU_SEGUNDO_NOMBRE ?? '';
                        $primerApellido = $user->USU_PRIMER_APELLIDO ?? '';
                        $segundoApellido = $user->USU_SEGUNDO_APELLIDO ?? '';

                        // Armamos el nombre completo de manera limpia
                        $nombreCompleto = trim("{$primerNombre} {$segundoNombre} {$primerApellido} {$segundoApellido}");
                        $nombreUsuario = !empty($nombreCompleto) ? $nombreCompleto : ($user->name ?? 'Docente / Usuario');

                        $identificacionUsuario = $user->USU_CEDULA ?? 'N/A';
                        $emailUsuario = $user->USU_CORREO ?? 'No disponible';

                        $estadoReserva = $reserva->res_estado_reserva ?? 'pendiente';
                        $fechaIni = optional($primerDetalle)->det_re_fecha_ini;
                        $fechaFin = optional($primerDetalle)->det_re_fecha_fin;

                        $datosReservaModal = [
                            "id" => $reserva->res_id ?? $reserva->id,
                            "estado" => $estadoReserva,
                            "titulo" => "Detalle de Reserva #" . ($reserva->res_id ?? $reserva->id),
                            "solicitante" => $nombreUsuario,
                            "identificacion" => $identificacionUsuario,
                            "email" => $emailUsuario,
                            "motivo" => $reserva->res_motivo ?? ($reserva->motivo ?? "Sin motivo especificado."),
                            "fechaInicio" => $fechaIni ? \Carbon\Carbon::parse($fechaIni)->format("Y-m-d") : "N/A",
                            "horaInicio" => $fechaIni ? \Carbon\Carbon::parse($fechaIni)->format("h:i A") : "N/A",
                            "fechaFin" => $fechaFin ? \Carbon\Carbon::parse($fechaFin)->format("Y-m-d") : "N/A",
                            "horaFin" => $fechaFin ? \Carbon\Carbon::parse($fechaFin)->format("h:i A") : "N/A",
                            "aula" => $ubicacion,
                            "recursos" => $listaRecursosMultiples
                        ];

                        return [
                            'modal' => json_encode($datosReservaModal),
                            'id' => $reserva->res_id ?? $reserva->id,
                            'foto' => $fotoPrincipal,
                            'nombre' => $nombreRecurso,
                            'estado' => $estadoReserva,
                            'solicitante' => $nombreUsuario,
                            'fecha' => $fechaIni ? \Carbon\Carbon::parse($fechaIni)->format('d \d\e F Y') : 'N/A',
                            'horaInicio' => $fechaIni ? \Carbon\Carbon::parse($fechaIni)->format('H:i') : '08:00 AM',
                            'horaFin' => $fechaFin ? \Carbon\Carbon::parse($fechaFin)->format('H:i') : '10:00 AM',
                            'ubicacion' => $ubicacion,
                            'esMultiple' => $esMultiple,
                            'recursos' => $listaRecursosMultiples
                        ];
                    };

                    $pendientes = $reservas->filter(fn($r) => strtolower($r->res_estado_reserva ?? 'pendiente') === 'pendiente');
                    $aprobadas  = $reservas->filter(fn($r) => in_array(strtolower($r->res_estado_reserva ?? ''), ['aprobada', 'aprobado']));
                    $rechazadas = $reservas->filter(fn($r) => in_array(strtolower($r->res_estado_reserva ?? ''), ['rechazada', 'rechazado']));
                @endphp

                {{-- CONTENEDOR 1: PENDIENTES --}}
                <div id="seccion-pendiente" class="contenedor-grupo-estado">
                    @forelse($pendientes as $reserva)
                        @php $d = $generarHtmlTarjeta($reserva); @endphp
                        <div class="tarjeta-wrapper recurso-item" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalgeneral" data-reserva="{{ $d['modal'] }}" onclick="cargarDatosModalReserva(this)">
                            @component('components.tarjetas.tarjeta-reserva', [
                                'id' => $d['id'], 'foto' => $d['foto'], 'nombre' => $d['nombre'], 'estado' => $d['estado'],
                                'solicitante' => $d['solicitante'], 'fecha' => $d['fecha'], 'horaInicio' => $d['horaInicio'],
                                'horaFin' => $d['horaFin'], 'ubicacion' => $d['ubicacion'], 'urlGestion' => '#',
                                'esMultiple' => $d['esMultiple'], 'recursos' => $d['recursos']
                            ])
                            @endcomponent
                        </div>
                    @empty
                        <p class="text-center text-muted py-3 mensaje-vacio-pendiente">No hay solicitudes pendientes.</p>
                    @endforelse
                </div>

                {{-- CONTENEDOR 2: APROBADAS --}}
                <div id="seccion-aprobada" class="contenedor-grupo-estado" style="display: none;">
                    @forelse($aprobadas as $reserva)
                        @php $d = $generarHtmlTarjeta($reserva); @endphp
                        <div class="tarjeta-wrapper recurso-item" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalgeneral" data-reserva="{{ $d['modal'] }}" onclick="cargarDatosModalReserva(this)">
                            @component('components.tarjetas.tarjeta-reserva', [
                                'id' => $d['id'], 'foto' => $d['foto'], 'nombre' => $d['nombre'], 'estado' => $d['estado'],
                                'solicitante' => $d['solicitante'], 'fecha' => $d['fecha'], 'horaInicio' => $d['horaInicio'],
                                'horaFin' => $d['horaFin'], 'ubicacion' => $d['ubicacion'], 'urlGestion' => '#',
                                'esMultiple' => $d['esMultiple'], 'recursos' => $d['recursos']
                            ])
                            @endcomponent
                        </div>
                    @empty
                        <p class="text-center text-muted py-3 mensaje-vacio-aprobada">No hay reservas aprobadas.</p>
                    @endforelse
                </div>

                {{-- CONTENEDOR 3: RECHAZADAS --}}
                <div id="seccion-rechazada" class="contenedor-grupo-estado" style="display: none;">
                    @forelse($rechazadas as $reserva)
                        @php $d = $generarHtmlTarjeta($reserva); @endphp
                        <div class="tarjeta-wrapper recurso-item" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalgeneral" data-reserva="{{ $d['modal'] }}" onclick="cargarDatosModalReserva(this)">
                            @component('components.tarjetas.tarjeta-reserva', [
                                'id' => $d['id'], 'foto' => $d['foto'], 'nombre' => $d['nombre'], 'estado' => $d['estado'],
                                'solicitante' => $d['solicitante'], 'fecha' => $d['fecha'], 'horaInicio' => $d['horaInicio'],
                                'horaFin' => $d['horaFin'], 'ubicacion' => $d['ubicacion'], 'urlGestion' => '#',
                                'esMultiple' => $d['esMultiple'], 'recursos' => $d['recursos']
                            ])
                            @endcomponent
                        </div>
                    @empty
                        <p class="text-center text-muted py-3 mensaje-vacio-rechazada">No hay reservas rechazadas.</p>
                    @endforelse
                </div>

            </div>
        </div>

        <div class="columna-agenda-permanente">
            @php
                $reservasCalendario = $reservas->filter(function($reserva) {
                    $estado = strtolower(trim($reserva->res_estado_reserva ?? ''));
                    return $estado !== 'rechazada' && $estado !== 'rechazado';
                });

                $eventosCalendario = $reservasCalendario->map(function($reserva) use ($generarHtmlTarjeta) {
                    $totalDetalles = $reserva->detalles->count();
                    $primerDetalle = $reserva->detalles->first();
                    
                    if ($totalDetalles > 1) {
                        $nombreRecurso = "Reserva Múltiple ({$totalDetalles} ítems)";
                    } else {
                        $nombreRecurso = 'Recurso';
                        if ($primerDetalle) {
                            $nombreRecurso = optional($primerDetalle->activo)->act_nombre ?? 
                                             (optional($primerDetalle->activo)->nombre ?? 
                                             (optional($primerDetalle->aula)->aula_nombre ?? 
                                             (optional($primerDetalle->aula)->nombre ?? 'Recurso')));
                        }
                    }
                    
                    $user = $reserva->usuario;
                    $nombreUsuario = $user->nombres ?? ($user->name ?? ($user->nombre ?? 'Usuario'));
                    $estado = strtolower(trim($reserva->res_estado_reserva ?? 'pendiente'));

                    $esAprobada = in_array($estado, ['aprobada', 'aprobado']);
                    $colorFondo = $esAprobada ? '#198754' : '#ffc107'; 
                    $colorTexto = $esAprobada ? '#ffffff' : '#000000';

                    $d = $generarHtmlTarjeta($reserva);
                    
                    $rawFechaIni = optional($primerDetalle)->det_re_fecha_ini 
                                ?? optional($primerDetalle)->fecha_inicio 
                                ?? $reserva->res_fecha_inicio 
                                ?? $reserva->fecha_inicio 
                                ?? $reserva->created_at;

                    $rawFechaFin = optional($primerDetalle)->det_re_fecha_fin 
                                ?? optional($primerDetalle)->fecha_fin 
                                ?? $reserva->res_fecha_fin 
                                ?? $reserva->fecha_fin 
                                ?? $rawFechaIni;

                    $fechaInicio = $rawFechaIni ? \Carbon\Carbon::parse($rawFechaIni)->toIso8601String() : null;
                    $fechaFin = $rawFechaFin ? \Carbon\Carbon::parse($rawFechaFin)->toIso8601String() : null;

                    return [
                        'title' => $nombreRecurso . ' - ' . $nombreUsuario,
                        'start' => $fechaInicio,
                        'end'   => $fechaFin,
                        'backgroundColor' => $colorFondo,
                        'borderColor'     => $colorFondo,
                        'textColor'       => $colorTexto,
                        'extendedProps' => [
                            'recurso' => $nombreRecurso,
                            'usuario' => $nombreUsuario,
                            'estado'  => ucfirst($estado),
                            'modalData' => $d['modal']
                        ]
                    ];
                })->filter(fn($evento) => !empty($evento['start']))->values()->toArray();
            @endphp

            <style>
                #calendar {
                    background: #fff;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.05);
                    font-family: inherit;
                }
                #calendar .fc-button-primary {
                    background-color: #198754 !important;
                    border-color: #198754 !important;
                    color: #ffffff !important;
                    border-radius: 6px !important;
                    box-shadow: none !important;
                    font-weight: 500;
                }
                #calendar .fc-button-primary:hover {
                    background-color: #157347 !important;
                    border-color: #146c43 !important;
                }
                #calendar .fc-button-active {
                    background-color: #146c43 !important;
                    border-color: #13653f !important;
                }
                #calendar .fc-today-button {
                    background-color: #e9ecef !important;
                    border-color: #ced4da !important;
                    color: #495057 !important;
                    border-radius: 6px !important;
                }
                #calendar .fc-today-button:hover {
                    background-color: #dde2e6 !important;
                    color: #212529 !important;
                }
                #calendar .fc-toolbar-title {
                    color: #333333;
                    font-size: 1.35rem;
                    font-weight: 600;
                    text-transform: capitalize;
                }
                #calendar .fc-col-header-cell-cushion {
                    color: #495057;
                    font-weight: 600;
                    text-decoration: none;
                    text-transform: lowercase;
                    padding: 8px 0;
                }
                #calendar .fc-day-today {
                    background-color: #d1e7dd !important; 
                }
                #calendar .fc-scrollgrid {
                    border-color: #dee2e6 !important;
                    border-radius: 6px;
                }
                #calendar th, #calendar td {
                    border-color: #dee2e6 !important;
                }
            </style>

            <p class="text-muted mb-2" style="font-size: 0.85rem; font-weight: 500;">Eventos programados</p>
            <div id="calendar" style="width: 100%; min-height: 550px;"></div>

            <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css' rel='stylesheet' />
            <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var calendarEl = document.getElementById('calendar');
                    if (calendarEl) {
                        var calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'dayGridMonth',
                            locale: 'es',
                            firstDay: 1,
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek'
                            },
                            buttonText: {
                                today: 'Hoy',
                                month: 'Mes',
                                week: 'Semana',
                                list: 'Lista'
                            },
                            events: @json($eventosCalendario),
                            eventClick: function(info) {
                                if (info.event.extendedProps && info.event.extendedProps.modalData) {
                                    const modalElement = document.getElementById('modalgeneral');
                                    if (modalElement) {
                                        const dummyElement = {
                                            getAttribute: (attr) => attr === 'data-reserva' ? info.event.extendedProps.modalData : null
                                        };
                                        if (typeof cargarDatosModalReserva === 'function') {
                                            cargarDatosModalReserva(dummyElement);
                                        }
                                        var bsModal = new bootstrap.Modal(modalElement);
                                        bsModal.show();
                                    }
                                }
                            }
                        });
                        calendar.render();
                    }
                });
            </script>
        </div>
    </div>

    {{-- Renderizado Único del Componente Modal --}}
    <x-reservas.modal-detalle-reserva :esAdmin="$esAdmin" />

</div>

<script src="{{ asset('js/componentes/filtros-inventario.js') }}"></script>

<script>
    function cargarDatosModalReserva(elemento) {
        try {
            const targetEl = elemento && typeof elemento.getAttribute === 'function' 
                ? elemento 
                : (elemento && elemento.currentTarget ? elemento.currentTarget : null);

            if (!targetEl) return;

            const rawData = targetEl.getAttribute('data-reserva');
            if (!rawData) return;
            const datos = JSON.parse(rawData);

            const tituloEl = document.getElementById('modalgeneral-titulo');
            if (tituloEl) tituloEl.innerText = datos.titulo;

            const setearTexto = (id, valor) => {
                const el = document.getElementById(id);
                if (el) el.innerText = (valor !== null && valor !== undefined && valor !== '') ? valor : 'N/A';
            };

            setearTexto('resumen-solicitante', datos.solicitante);
            setearTexto('resumen-identificacion', datos.identificacion);
            setearTexto('resumen-email', datos.email);
            setearTexto('resumen-motivo', datos.motivo);
            setearTexto('resumen-fecha-inicio', datos.fechaInicio);
            setearTexto('resumen-hora-inicio', datos.horaInicio);
            setearTexto('resumen-fecha-fin', datos.fechaFin);
            setearTexto('resumen-hora-fin', datos.horaFin);
            setearTexto('resumen-aula-uso', datos.aula);

            const contenedorRecursos = document.getElementById('resumen-bloque-recurso');
            if (contenedorRecursos && datos.recursos) {
                let htmlRecursos = `
                    <style>
                        .item-recurso-hover { background-color: #ffffff; border-color: #dee2e6 !important; transition: background-color 0.2s ease, border-color 0.2s ease; }
                        .item-recurso-hover:hover { background-color: #d1e7dd !important; border-color: #badbcc !important; }
                    </style>
                    <h3 class="mb-3" style="font-size: 1.1rem; font-weight: 600; color: #212529;">Recursos Seleccionados (${datos.recursos.length})</h3>
                    <div id="contenedor-acordeon-recursos" class="border rounded p-3" style="border-color: #dee2e6 !important; transition: border-color 0.2s ease;">
                        <div style="cursor: pointer; display: flex; justify-content: space-between; align-items: center;" onclick="toggleAcordeonRecursos()">
                            <span style="font-weight: 600; color: #212529;">Lista de recursos (${datos.recursos.length})</span>
                            <i id="icono-acordeon-flecha" class="fas fa-chevron-down" style="transition: transform 0.3s ease;"></i>
                        </div>
                        <div id="acordeon-recursos-body" class="mt-3" style="display: none;">
                `;
                
                datos.recursos.forEach(rec => {
                    let detallesHTML = '';
                    if (rec.es_aula === true) {
                        detallesHTML = `
                            <p class="mb-1 text-muted" style="font-size: 0.9rem;">Capacidad: ${rec.serial} personas</p>
                            <p class="mb-0 text-muted" style="font-size: 0.9rem;">${rec.marca}</p>
                        `;
                    } else {
                        detallesHTML = `
                            <p class="mb-1 text-muted" style="font-size: 0.9rem;">Número de serie: ${rec.serial}</p>
                            <p class="mb-0 text-muted" style="font-size: 0.9rem;">Marca: ${rec.marca}</p>
                        `;
                    }

                    htmlRecursos += `
                        <div class="border p-3 mb-2 rounded item-recurso-hover d-flex align-items-center gap-3" style="cursor: default;">
                            <img src="${rec.foto}" alt="Foto recurso" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                            <div>
                                <p class="mb-1" style="font-weight: 600; color: #212529;">${rec.nombre}</p>
                                ${detallesHTML}
                            </div>
                        </div>
                    `;
                });
                
                htmlRecursos += `</div></div>`;
                contenedorRecursos.innerHTML = htmlRecursos;
            }

            const formRechazar = document.getElementById('formRechazar');
            const formAprobar = document.getElementById('formAprobar');
            const formRevertir = document.getElementById('formRevertir');
            if (formRechazar) formRechazar.action = `/secretaria/reservas/${datos.id}/rechazar`;
            if (formAprobar) formAprobar.action = `/secretaria/reservas/${datos.id}/aprobar`;
            if (formRevertir) formRevertir.action = `/secretaria/reservas/${datos.id}/revertir`;

            const bloquePendiente = document.getElementById('bloque-acciones-pendiente');
            const bloqueRevertir = document.getElementById('bloque-acciones-revertir');
            const estadoRes = (datos.estado || '').toLowerCase().trim();

            if (bloquePendiente && bloqueRevertir) {
                bloquePendiente.style.setProperty('display', 'none', 'important');
                bloqueRevertir.style.setProperty('display', 'none', 'important');
                if (estadoRes === 'pendiente') {
                    bloquePendiente.style.setProperty('display', 'flex', 'important');
                } else if (['aprobada', 'rechazada', 'aprobado', 'rechazado'].includes(estadoRes)) {
                    bloqueRevertir.style.setProperty('display', 'flex', 'important');
                }
            }
        } catch (error) {
            console.error("Error al procesar los datos del modal:", error);
        }
    }

    window.toggleAcordeonRecursos = function() {
        const body = document.getElementById('acordeon-recursos-body');
        const flecha = document.getElementById('icono-acordeon-flecha');
        const contenedor = document.getElementById('contenedor-acordeon-recursos');
        
        if (body) {
            const isHidden = body.style.display === 'none';
            body.style.display = isHidden ? 'block' : 'none';
            if (flecha) flecha.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
            if (contenedor) contenedor.style.setProperty('border-color', isHidden ? '#198754' : '#dee2e6', 'important');
        }
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        window.filtrarPorEstado = function(filtro) {
            const seccionPendiente = document.getElementById('seccion-pendiente');
            const seccionAprobada = document.getElementById('seccion-aprobada');
            const seccionRechazada = document.getElementById('seccion-rechazada');

            if (seccionPendiente) seccionPendiente.style.display = 'none';
            if (seccionAprobada) seccionAprobada.style.display = 'none';
            if (seccionRechazada) seccionRechazada.style.display = 'none';

            const seccionActiva = document.getElementById('seccion-' + filtro);
            if (seccionActiva) {
                seccionActiva.style.display = 'block';
            }
        };

        document.querySelectorAll('[data-filtro], .kpi-card, .tarjeta-kpi').forEach(elemento => {
            elemento.style.cursor = 'pointer';
            elemento.addEventListener('click', function(e) {
                const filtro = this.getAttribute('data-filtro') || this.querySelector('[data-filtro]')?.getAttribute('data-filtro');
                
                if (filtro) {
                    e.preventDefault();
                    filtrarPorEstado(filtro);
                }
            });
        });
    });
</script>
@endsection