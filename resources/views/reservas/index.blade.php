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
                        
                        $user = $reserva->usuario;
                        
                        $primerNombre = $user->USU_PRIMER_NOMBRE ?? '';
                        $segundoNombre = $user->USU_SEGUNDO_NOMBRE ?? '';
                        $primerApellido = $user->USU_PRIMER_APELLIDO ?? '';
                        $segundoApellido = $user->USU_SEGUNDO_APELLIDO ?? '';

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

        {{-- COLUMNA DERECHA: AGENDA Y CALENDARIO --}}
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
                    $colorFondo = $esAprobada ? '#22c55e' : '#facc15'; 
                    $colorTexto = $esAprobada ? '#ffffff' : '#444444';

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
                })->values();
            @endphp

            {{-- Componente Agenda/Calendario --}}
            @include('components.agendas.agenda', ['eventos' => $eventosCalendario])
        </div>

    </div>
</div>

<script>
function cargarDatosModalReserva(elemento) {
    const jsonStr = elemento.getAttribute('data-reserva');
    if (!jsonStr) return;
    try {
        const data = JSON.parse(jsonStr);
        if (typeof cargarDatosModal === 'function') {
            cargarDatosModal(data);
        }
    } catch (e) {
        console.error("Error interpretando datos del modal:", e);
    }
}
</script>
@endsection