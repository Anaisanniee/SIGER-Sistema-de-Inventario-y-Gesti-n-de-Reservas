{{-- resources/views/components/agendas/agenda.blade.php --}}

@props([
    'reservas' => collect([]),
    'eventos' => []
])

@php
    // Si se pasa la colección de reservas, procesamos la lógica completa del primer bloque
    if ($reservas->isNotEmpty()) {
        $reservasCalendario = $reservas->filter(function($reserva) {
            $estado = strtolower(trim($reserva->res_estado_reserva ?? ''));
            return $estado !== 'rechazada' && $estado !== 'rechazado';
        });

        $eventosCalculados = $reservasCalendario->map(function($reserva) {
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

            // Extracción de datos del usuario
            $user = $reserva->usuario;
            $primerNombre = $user->USU_PRIMER_NOMBRE ?? '';
            $segundoNombre = $user->USU_SEGUNDO_NOMBRE ?? '';
            $primerApellido = $user->USU_PRIMER_APELLIDO ?? '';
            $segundoApellido = $user->USU_SEGUNDO_APELLIDO ?? '';

            $nombreCompleto = trim("{$primerNombre} {$segundoNombre} {$primerApellido} {$segundoApellido}");
            $nombreUsuario = !empty($nombreCompleto) ? $nombreCompleto : ($user->nombres ?? ($user->name ?? ($user->nombre ?? 'Docente / Usuario')));

            $identificacionUsuario = $user->USU_CEDULA ?? 'N/A';
            $emailUsuario = $user->USU_CORREO ?? 'No disponible';

            $estadoReserva = $reserva->res_estado_reserva ?? 'pendiente';
            $fechaIni = optional($primerDetalle)->det_re_fecha_ini ?? $reserva->res_fecha_inicio ?? $reserva->fecha_inicio ?? $reserva->created_at;
            $fechaFin = optional($primerDetalle)->det_re_fecha_fin ?? $reserva->res_fecha_fin ?? $reserva->fecha_fin ?? $fechaIni;

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

            $esAprobada = in_array(strtolower(trim($estadoReserva)), ['aprobada', 'aprobado']);
            $colorFondo = $esAprobada ? '#198754' : '#ffc107'; 
            $colorTexto = $esAprobada ? '#ffffff' : '#000000';

            return [
                'title' => $nombreRecurso . ' - ' . $nombreUsuario,
                'start' => $fechaIni ? \Carbon\Carbon::parse($fechaIni)->toIso8601String() : null,
                'end'   => $fechaFin ? \Carbon\Carbon::parse($fechaFin)->toIso8601String() : null,
                'backgroundColor' => $colorFondo,
                'borderColor'     => $colorFondo,
                'textColor'       => $colorTexto,
                'extendedProps' => [
                    'recurso'   => $nombreRecurso,
                    'usuario'   => $nombreUsuario,
                    'estado'    => ucfirst($estadoReserva),
                    'modalData' => json_encode($datosReservaModal)
                ]
            ];
        })->filter(fn($evento) => !empty($evento['start']))->values()->toArray();

        $eventosFinales = $eventosCalculados;
    } else {
        $eventosFinales = $eventos;
    }
@endphp

<div class="tarjeta-blanca-datos p-3 shadow-sm rounded-4 w-100">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h6 class="fw-bold mb-0" style="color: var(--color-texto); font-family: var(--fuente-secundaria);">
                <i class="bi bi-calendar3 me-2" style="color: var(--color-principal);"></i>Agenda de Reservas
            </h6>
            <small style="color: var(--color-texto-secundario); font-size: 0.8rem;">Eventos agendados</small>
        </div>
    </div>

    {{-- Contenedor de FullCalendar con contención de desborde --}}
    <div id="calendario-secretaria-container" class="w-100 overflow-hidden">
        <div id="calendario-secretaria" style="min-height: 480px;"></div>
    </div>
</div>

<style>
    /* =========================================================================
       ESTILOS UNIFICADOS DE FULLCALENDAR
       ========================================================================= */
    #calendario-secretaria {
        background-color: var(--color-fondo);
        font-family: var(--fuente-principal);
    }

    #calendario-secretaria .fc-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem !important;
    }

    #calendario-secretaria .fc-toolbar-title {
        color: var(--color-texto) !important;
        font-family: var(--fuente-secundaria) !important;
        font-size: 1.25rem !important;
        font-weight: 700;
        text-transform: capitalize;
    }

    #calendario-secretaria .fc-button-group {
        gap: 0.3rem;
    }

    /* Estilo para los botones de navegación y vistas */
    #calendario-secretaria .fc-button {
        background-color: var(--color-principal) !important;
        border-color: var(--color-principal) !important;
        color: var(--color-fondo) !important;
        font-family: var(--fuente-principal) !important;
        border-radius: var(--borde-radio) !important;
        box-shadow: none !important;
        font-weight: 500;
        padding: 0.375rem 0.75rem;
        text-transform: capitalize;
        transition: all 0.2s ease-in-out;
    }

    #calendario-secretaria .fc-button:hover,
    #calendario-secretaria .fc-button:focus {
        background-color: var(--principal-secundario) !important;
        border-color: var(--principal-secundario) !important;
        color: var(--color-fondo) !important;
    }

    #calendario-secretaria .fc-button-primary:not(:disabled).fc-button-active, 
    #calendario-secretaria .fc-button-primary:not(:disabled):active {
        background-color: var(--principal-secundario) !important;
        border-color: var(--principal-secundario) !important;
        color: var(--color-fondo) !important;
        font-weight: 700;
    }

    #calendario-secretaria .fc-button:disabled {
        background-color: var(--color-fondo-secundario) !important;
        border-color: var(--color-fondo-secundario) !important;
        color: var(--color-texto-secundario) !important;
        opacity: 0.7;
    }

    /* Malla, Bordes y Encabezados */
    #calendario-secretaria .fc-scrollgrid {
        border-color: var(--color-borde) !important;
        border-radius: var(--borde-radio);
    }

    #calendario-secretaria .fc-theme-standard td, 
    #calendario-secretaria .fc-theme-standard th {
        border-color: var(--color-borde) !important;
    }

    #calendario-secretaria .fc-col-header-cell-cushion {
        color: var(--color-texto-secundario) !important;
        font-family: var(--fuente-principal) !important;
        font-weight: 600;
        text-decoration: none !important;
        text-transform: capitalize;
        padding: 8px 0;
    }

    #calendario-secretaria .fc-daygrid-day-number {
        color: var(--color-texto) !important;
        text-decoration: none !important;
        font-weight: 500;
    }

    /* Resaltado de Día Actual */
    #calendario-secretaria .fc-day-today {
        background-color: var(--color-verde-pastel) !important;
    }

    /* Eventos y Tarjetas en el Calendario */
    #calendario-secretaria .fc-daygrid-event {
        border-radius: var(--borde-radio) !important;
        padding: 2px 6px !important;
        margin-top: 2px !important;
        border: none !important;
        font-size: 0.78rem !important;
        font-family: var(--fuente-principal) !important;
        cursor: pointer;
    }

    #calendario-secretaria .fc-event-main {
        font-weight: 500;
    }

    /* Adaptación Móvil Responsiva */
    @media (max-width: 576px) {
        #calendario-secretaria .fc-toolbar {
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }

        #calendario-secretaria .fc-toolbar-title {
            text-align: center;
            font-size: 1rem !important;
            order: -1;
        }

        #calendario-secretaria .fc-toolbar-chunk {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        #calendario-secretaria .fc-button {
            padding: 0.25rem 0.4rem !important;
            font-size: 0.75rem !important;
        }

        #calendario-secretaria .fc-col-header-cell-cushion,
        #calendario-secretaria .fc-daygrid-day-number {
            font-size: 0.75rem !important;
        }
    }
</style>

{{-- CDN de FullCalendar --}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core/locales/es.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendario-secretaria');
    if (!calendarEl) return;

    let eventosPhp = @json($eventosFinales);

    // Mapeo dinámico de variables CSS
    const rootStyles = getComputedStyle(document.documentElement);
    const colorDisponible = rootStyles.getPropertyValue('--color-estado-disponible').trim() || '#198754';
    const colorPendiente  = rootStyles.getPropertyValue('--color-estado-en-mantenimiento').trim() || '#ffc107';
    const colorDanado     = rootStyles.getPropertyValue('--color-estado-dañado').trim() || '#dc2626';
    const colorPrincipal  = rootStyles.getPropertyValue('--color-principal').trim() || '#10b981';
    const colorTexto      = rootStyles.getPropertyValue('--color-texto').trim() || '#444444';

    function obtenerColorEstado(estado) {
        const est = (estado || '').toLowerCase().trim();
        switch (est) {
            case 'aprobado':
            case 'aprobada':
            case 'confirmado':
                return colorDisponible;
            case 'pendiente':
                return colorPendiente;
            case 'rechazado':
            case 'rechazada':
            case 'cancelado':
                return colorDanado;
            default:
                return colorPrincipal;
        }
    }

    const eventosConColor = eventosPhp.map(evt => {
        const estado = evt.extendedProps?.estado || evt.estado || 'pendiente';
        const bg = evt.backgroundColor || evt.color || obtenerColorEstado(estado);
        
        return {
            ...evt,
            backgroundColor: bg,
            borderColor: bg,
            textColor: evt.textColor || ((estado.toLowerCase() === 'pendiente') ? '#000000' : '#ffffff')
        };
    });

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        firstDay: 1,
        height: 'auto',
        handleWindowResize: true,
        eventDisplay: 'block',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week:  'Semana'
        },
        events: eventosConColor,
        eventClick: function(info) {
            info.jsEvent.preventDefault();

            const props = info.event.extendedProps || {};
            const modalData = props.modalData;

            if (modalData) {
                const dummyElement = {
                    getAttribute: (attr) => attr === 'data-reserva' ? modalData : null
                };

                // Llama a la función global para cargar los datos en la vista principal
                if (typeof cargarDatosModalReserva === 'function') {
                    cargarDatosModalReserva(dummyElement);
                } else if (typeof cargarDatosModal === 'function') {
                    try {
                        const parsed = typeof modalData === 'string' ? JSON.parse(modalData) : modalData;
                        cargarDatosModal(parsed);
                    } catch (e) {
                        console.error("Error al procesar modalData:", e);
                    }
                }

                const modalEl = document.getElementById('modalgeneral');
                if (modalEl) {
                    const modalBs = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modalBs.show();
                }
            }
        }
    });

    calendar.render();
});
</script>