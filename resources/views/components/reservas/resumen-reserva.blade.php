{{-- resources/views/components/reservas/resumen-reserva.blade.php --}}
@props([
    'reserva' => null, 
    'mostrarSubtitulo' => true
])
    <style>
        /* Correcciones de estilo para la cabecera y el cuerpo del modal */
        #modalDetalleReserva .modal-titulo-dinamico,
        #modalDetalleReserva .modal-title,
        #modalDetalleReserva h4 {
            color: var(--principal-secudario)) !important;
            font-weight: 700 !important;
        }

        #modalDetalleReserva .modal-subtitle,
        #modalDetalleReserva h6 {
            color: var(--principal-secundario) !important;
            font-weight: 500 !important;
        }
    </style>
@php
    $reservaValida = $reserva ?? new \stdClass();

    $usuario = $reservaValida->usuario ?? null;
    $nombres = is_object($usuario) ? ($usuario->nombres ?? trim(($usuario->USU_PRIMER_NOMBRE ?? '') . ' ' . ($usuario->USU_SEGUNDO_NOMBRE ?? ''))) : 'Docente Solicitante';
    $apellidos = is_object($usuario) ? ($usuario->apellidos ?? trim(($usuario->USU_PRIMER_APELLIDO ?? '') . ' ' . ($usuario->USU_SEG_APELLIDO ?? ''))) : '';
    $nombreSolicitante = trim($nombres . ' ' . $apellidos) ?: ($usuario->name ?? 'Docente Solicitante');

    $identificacionUsuario = is_object($usuario) ? ($usuario->identificacion ?? ($usuario->cedula ?? ($usuario->USU_CEDULA ?? 'N/A'))) : 'N/A';
    $emailUsuario = is_object($usuario) ? ($usuario->email ?? ($usuario->USU_CORREO ?? 'correo@colegio.edu.co')) : 'correo@colegio.edu.co';

    $detalles = isset($reservaValida->detalles) ? $reservaValida->detalles : collect();
    $primerDetalle = $detalles->first() ?? null;

    $rawFechaIni = optional($primerDetalle)->det_re_fecha_ini ?? ($reservaValida->res_fecha_inicio ?? ($reservaValida->res_fecha_reserva ?? null));
    $rawFechaFin = optional($primerDetalle)->det_re_fecha_fin ?? ($reservaValida->res_fecha_fin ?? null);

    $fechaInicio = $rawFechaIni ? \Carbon\Carbon::parse($rawFechaIni)->format('Y-m-d') : 'N/A';
    $horaInicio = $rawFechaIni ? \Carbon\Carbon::parse($rawFechaIni)->format('h:i A') : 'N/A';
    
    $fechaFin = $rawFechaFin ? \Carbon\Carbon::parse($rawFechaFin)->format('Y-m-d') : $fechaInicio;
    $horaFin = $rawFechaFin ? \Carbon\Carbon::parse($rawFechaFin)->format('h:i A') : 'N/A';

    $motivoReserva = $reservaValida->res_motivo ?? ($reservaValida->motivo ?? 'Desarrollo de clase práctica y actividades pedagógicas programadas.');

    // Mapeo blindado: Detecta tanto objetos estructurados (Paso 3) como consultas directas de BD (Historial/Secretaría)
    $listaRecursos = $detalles->map(function($det) {
        $detObj = (object)$det;

        // Caso A: Viene estructurado desde el Paso 3
        if (isset($detObj->activo) && $detObj->activo) {
            $act = (object)$detObj->activo;
            return (object)[
                'tipo'            => 'activo',
                'nombre'          => $act->act_nombre ?? $act->nombre ?? 'Recurso',
                'serialCapacidad' => $act->act_serial ?? $act->serial ?? 'Sin Serial',
                'marcaCategoria'  => $act->act_marca ?? $act->marca ?? 'N/A',
                'imagen'          => $act->act_foto ?? $act->foto ?? null
            ];
        }
        
        if (isset($detObj->aula) && $detObj->aula) {
            $aul = (object)$detObj->aula;
            return (object)[
                'tipo'            => 'aula',
                'nombre'          => $aul->aula_nombre ?? $aul->nombre ?? 'Salón',
                'serialCapacidad' => $aul->aula_capacidad ?? $aul->capacidad ?? 'N/A',
                'marcaCategoria'  => 'Espacio Institucional',
                'imagen'          => $aul->aula_foto ?? $aul->foto ?? null
            ];
        }

        // Caso B: Viene directo de la Base de Datos (Historial / Secretaría)
        $activoId = $detObj->act_id ?? $detObj->activo_id ?? $detObj->id_activo ?? null;
        if (!empty($activoId)) {
            $activoDb = \DB::table('activos')->where('act_id', $activoId)->first() 
                        ?? \DB::table('activos')->where('id', $activoId)->first();

            return (object)[
                'tipo'            => 'activo',
                'nombre'          => $activoDb->act_nombre ?? $activoDb->nombre ?? ('Activo #' . $activoId),
                'serialCapacidad' => $activoDb->act_serial ?? $activoDb->serial ?? 'N/A',
                'marcaCategoria'  => $activoDb->act_marca ?? $activoDb->marca ?? 'N/A',
                'imagen'          => $activoDb->act_foto ?? $activoDb->foto ?? null
            ];
        } else {
            $aulaId = $detObj->det_re_aula_destino_act ?? $detObj->aula_id ?? $detObj->id_aula ?? null;
            $aulaDb = null;
            if (!empty($aulaId)) {
                $aulaDb = \DB::table('aulas')->where('aula_id', $aulaId)->first() 
                          ?? \DB::table('aulas')->where('id', $aulaId)->first();
            }

            return (object)[
                'tipo'            => 'aula',
                'nombre'          => $aulaDb->aula_nombre ?? $aulaDb->nombre ?? ('Aula Destino #' . ($aulaId ?? 'General')),
                'serialCapacidad' => $aulaDb->aula_capacidad ?? $aulaDb->capacidad ?? 'N/A',
                'marcaCategoria'  => 'Espacio Institucional',
                'imagen'          => $aulaDb->aula_foto ?? $aulaDb->foto ?? null
            ];
        }
    })->filter()->values();

    $cantidadRecursos = $listaRecursos->count();
    $esMultiple = $cantidadRecursos > 1;

    // Recuperar ubicación de destino correctamente
    $aulaUso = 'N/A';
    if ($primerDetalle) {
        if (isset($primerDetalle->aula) && is_object($primerDetalle->aula)) {
            $aulaUso = $primerDetalle->aula->aula_nombre ?? $primerDetalle->aula->nombre ?? 'N/A';
        } else {
            $aulaIdUbicacion = $primerDetalle->det_re_aula_destino_act ?? $primerDetalle->aula_id ?? null;
            if ($aulaIdUbicacion) {
                $dbAulaUbicacion = \DB::table('aulas')->where('aula_id', $aulaIdUbicacion)->first() 
                                   ?? \DB::table('aulas')->where('id', $aulaIdUbicacion)->first();
                $aulaUso = $dbAulaUbicacion->aula_nombre ?? $dbAulaUbicacion->nombre ?? $aulaIdUbicacion;
            }
        }
    }

    // JSON seguro para el modal interactivo
    $datosModalArray = [
        'id'             => $reservaValida->res_id ?? $reservaValida->id ?? '',
        'titulo'         => 'Detalles de la Reserva #' . ($reservaValida->res_id ?? $reservaValida->id ?? ''),
        'solicitante'    => $nombreSolicitante,
        'identificacion' => $identificacionUsuario,
        'email'          => $emailUsuario,
        'motivo'         => $motivoReserva,
        'fechaInicio'    => $fechaInicio,
        'horaInicio'     => $horaInicio,
        'fechaFin'       => $fechaFin,
        'horaFin'        => $horaFin,
        'aula'           => $aulaUso,
        'estado'         => $reservaValida->res_estado ?? $reservaValida->estado ?? 'pendiente',
        'recursos'       => $listaRecursos->map(function($rec) {
            return [
                'nombre'  => $rec->nombre,
                'serial'  => $rec->serialCapacidad,
                'marca'   => $rec->marcaCategoria,
                'foto'    => !empty($rec->imagen) ? asset('storage/' . $rec->imagen) : asset('images/default-resource.png'),
                'es_aula' => $rec->tipo === 'aula'
            ];
        })->toArray()
    ];

    $jsonModalReserva = json_encode($datosModalArray, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
@endphp

<style>
    .dropdown-menu-recursos {
        border: 1px solid #198754 !important;
        border-radius: 0.75rem !important;
        padding: 0.75rem !important;
        background-color: var(--color-fondo) !important;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .item-recurso-card {
        transition: background-color 0.2s ease, border-color 0.2s ease;
        border: 1px solid #e5e7eb;
        background-color: var(--color-fondo);
    }
    .item-recurso-card:hover {
        background-color: var(--color-verde-pastel) !important;
        border-color: #a7f3d0 !important;
    }
</style>

<div class="tarjeta-reserva-siger overflow-hidden rounded-4 border shadow-sm bg-white" data-reserva='{!! $jsonModalReserva !!}'>

    <div class="p-4">
        <div class="mb-4">
            <h3 class="fw-bold text-dark mb-1">Resumen de Reserva</h3>
            @if($mostrarSubtitulo)
                <p class="text-muted small mb-0">Por favor, verifique todos los datos antes de confirmar la solicitud del recurso.</p>
            @endif
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <div class="p-3 h-100 border rounded-3 bg-white shadow-sm">
                    <h6 class="fw-bold text-dark mb-3">Datos del Solicitante</h6>
                    <p class="mb-2"><strong>Nombre:</strong> <span id="resumen-solicitante">{{ $nombreSolicitante }}</span></p>
                    <p class="mb-2"><strong>Identificación:</strong> <span id="resumen-identificacion">{{ $identificacionUsuario }}</span></p>
                    <p class="mb-0"><strong>Correo Electrónico:</strong> <span id="resumen-email">{{ $emailUsuario }}</span></p>
                </div>
            </div>

            <div class="col-md-6" id="resumen-bloque-recurso">
                <div class="p-3 h-100 border rounded-3 bg-white shadow-sm">
                    <h6 class="fw-bold text-dark mb-3">Recursos Seleccionados ({{ $cantidadRecursos }})</h6>
                    @if($esMultiple)
                        <div class="dropdown">
                            <button class="btn bg-white text-dark w-100 text-start d-flex justify-content-between align-items-center dropdown-toggle shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.6rem 1rem;">
                                Lista de recursos ({{ $cantidadRecursos }})
                            </button>
                            <ul class="dropdown-menu dropdown-menu-recursos w-100">
                                @foreach($listaRecursos as $rec)
                                    <li class="mb-2 last-mb-0">
                                        <div class="item-recurso-card p-2 rounded-3 d-flex align-items-center gap-3">
                                            <div class="border rounded bg-light d-flex align-items-center justify-content-center overflow-hidden" style="width: 50px; height: 50px; min-width: 50px;">
                                                @if(!empty($rec->imagen))
                                                    <img src="{{ asset('storage/' . $rec->imagen) }}" alt="{{ $rec->nombre }}" class="w-100 h-100 object-fit-cover">
                                                @else
                                                    <i class="bi {{ $rec->tipo === 'activo' ? 'bi-laptop text-primary' : 'bi-door-open text-success' }} fs-5"></i>
                                                @endif
                                            </div>
                                            <div class="overflow-hidden">
                                                <h6 class="fw-bold text-dark mb-1 text-truncate small">{{ $rec->nombre }}</h6>
                                                <p class="text-muted mb-0" style="font-size: 0.72rem; line-height: 1.2;">
                                                    <span class="d-block">{{ $rec->tipo === 'activo' ? 'Número de serie: ' : 'Capacidad: ' }}{{ $rec->serialCapacidad }}</span>
                                                    <span class="d-block">{{ $rec->marcaCategoria }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <p class="mb-0"><strong>Recurso:</strong> {{ $listaRecursos[0]->nombre ?? 'Recurso único' }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-3 mb-3 border rounded-3 bg-white shadow-sm">
            <h6 class="fw-bold text-dark mb-2">Motivo de la Solicitud</h6>
            <p class="text-muted mb-0"><span id="resumen-motivo">{{ $motivoReserva }}</span></p>
        </div>

        <div class="p-3 mb-3 border rounded-3 bg-white shadow-sm">
            <h6 class="fw-bold text-dark mb-3">Asignación de Tiempos</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 border rounded bg-light h-100">
                        <span class="text-dark fw-bold small d-block mb-2">INICIO DE RESERVA</span>
                        <p class="mb-1"><strong>Fecha:</strong> <span id="resumen-fecha-inicio">{{ $fechaInicio }}</span></p>
                        <p class="mb-0"><strong>Hora:</strong> <span id="resumen-hora-inicio">{{ $horaInicio }}</span></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border rounded bg-light h-100">
                        <span class="text-dark fw-bold small d-block mb-2">FINALIZACIÓN DE RESERVA</span>
                        <p class="mb-1"><strong>Fecha:</strong> <span id="resumen-fecha-fin">{{ $fechaFin }}</span></p>
                        <p class="mb-0"><strong>Hora:</strong> <span id="resumen-hora-fin">{{ $horaFin }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        @if($aulaUso !== 'N/A')
            <div class="p-3 border rounded-3 bg-white shadow-sm">
                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-geo-alt-fill text-danger me-1"></i> Ubicación de Destino Asignada</h6>
                <p class="text-muted small mb-1">El recurso será trasladado pedagógicamente para su uso en el siguiente espacio:</p>
                <p class="mb-0"><strong>Lugar de uso:</strong> <span class="badge bg-success" id="resumen-aula-uso">{{ $aulaUso }}</span></p>
            </div>
        @endif
    </div>
</div>