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
                ['filtro' => 'pendiente',  'color' => 'amarillo','icono' => 'fas fa-clock',      'titulo' => 'Pendientes',  'subtitulo' => 'Por revisar'],
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
                @forelse($reservas as $reserva)
                    @php
                        $tagsReserva = ['reserva', strtolower($reserva->res_estado ?? $reserva->estado ?? 'pendiente')];
                        $strTagsReserva = implode(' ', $tagsReserva);
                        
                        $totalDetalles = $reserva->detalles->count();
                        $esMultiple = $totalDetalles > 1;

                        // Determinar nombre del recurso principal o si es múltiple
                        $primerDetalle = $reserva->detalles->first();
                        if ($esMultiple) {
                            $nombreRecurso = "Reserva Múltiple ({$totalDetalles} ítems)";
                        } else {
                            $nombreRecurso = 'Recurso General';
                            if ($primerDetalle) {
                                if ($primerDetalle->activo) {
                                    $nombreRecurso = $primerDetalle->activo->act_nombre ?? 'Activo sin nombre';
                                } elseif ($primerDetalle->aula) {
                                    $nombreRecurso = $primerDetalle->aula->aula_nombre ?? 'Aula sin nombre';
                                }
                            }
                        }

                        // Ubicación y datos del solicitante
                        $ubicacion = $primerDetalle->aula->aula_nombre ?? 'N/A';
                        $nombreUsuario = $reserva->usuario->nombres ?? ($reserva->usuario->name ?? 'Usuario');
                        $estadoReserva = $reserva->res_estado ?? ($reserva->estado ?? 'pendiente');

                        // Fechas y horas extraídas de los detalles de la reserva
                        $fechaIni = optional($primerDetalle)->det_re_fecha_ini;
                        $fechaFin = optional($primerDetalle)->det_re_fecha_fin;

                        // Mapeo seguro de la lista de recursos para la tarjeta múltiple y el modal
                        $listaRecursosMultiples = $reserva->detalles->map(function($det) {
                            return (object)[
                                'nombre' => $det->activo->act_nombre ?? ($det->aula->aula_nombre ?? 'Elemento reservado'),
                                'serial' => $det->activo->act_serial ?? 'N/A',
                                'marca'  => $det->activo->act_marca ?? 'N/A'
                            ];
                        })->toArray();

                        // Estructura limpia para el JSON del modal
                        $datosReservaModal = [
                            "id" => $reserva->res_id ?? $reserva->id,
                            "estado" => $estadoReserva,
                            "titulo" => "Detalle de Reserva #" . ($reserva->res_id ?? $reserva->id),
                            "solicitante" => $nombreUsuario,
                            "identificacion" => $reserva->usuario->identificacion ?? ($reserva->usuario->cedula ?? "N/A"),
                            "email" => $reserva->usuario->email ?? "No disponible",
                            "motivo" => $reserva->res_motivo ?? ($reserva->motivo ?? "Sin motivo especificado."),
                            "fechaInicio" => $fechaIni ? \Carbon\Carbon::parse($fechaIni)->format("Y-m-d") : "N/A",
                            "horaInicio" => $fechaIni ? \Carbon\Carbon::parse($fechaIni)->format("h:i A") : "N/A",
                            "fechaFin" => $fechaFin ? \Carbon\Carbon::parse($fechaFin)->format("Y-m-d") : "N/A",
                            "horaFin" => $fechaFin ? \Carbon\Carbon::parse($fechaFin)->format("h:i A") : "N/A",
                            "aula" => $ubicacion,
                            "recursos" => $listaRecursosMultiples
                        ];
                    @endphp
                    
                    <div class="tarjeta-wrapper recurso-item" 
                        data-tags="{{ $strTagsReserva }}"
                        style="cursor: pointer;"
                        data-bs-toggle="modal" 
                        data-bs-target="#modalgeneral"
                        data-reserva="{{ json_encode($datosReservaModal) }}"
                        onclick="cargarDatosModalReserva(this)">
                        
                        @component('components.tarjetas.tarjeta-reserva', [
                            'id'          => $reserva->res_id ?? $reserva->id,
                            'foto'        => asset('storage/images/activos/default.jpeg'),
                            'nombre'      => $nombreRecurso,
                            'estado'      => $estadoReserva,
                            'solicitante' => $nombreUsuario,
                            'fecha'       => $fechaIni ? \Carbon\Carbon::parse($fechaIni)->format('d \d\e F Y') : 'N/A',
                            'horaInicio'  => $fechaIni ? \Carbon\Carbon::parse($fechaIni)->format('H:i') : '08:00 AM',
                            'horaFin'     => $fechaFin ? \Carbon\Carbon::parse($fechaFin)->format('H:i') : '10:00 AM',
                            'ubicacion'   => $ubicacion,
                            'urlGestion'  => '#',
                            'esMultiple'  => $esMultiple,
                            'recursos'    => $listaRecursosMultiples
                        ])
                        @endcomponent
                    </div>
                @empty
                    <p class="text-center text-muted" style="padding: 20px;">No hay solicitudes de reservas registradas.</p>
                @endforelse
            </div>
        </div>

        <!-- COLUMNA DERECHA: AGENDA PERMANENTE (DINÁMICA) -->
        <div class="columna-agenda-permanente">
            @php
                $eventosCalendario = $reservas->map(function($reserva) {
                    $totalDetalles = $reserva->detalles->count();
                    $primerDetalle = $reserva->detalles->first();
                    
                    if ($totalDetalles > 1) {
                        $nombreRecurso = "Reserva Múltiple ({$totalDetalles} ítems)";
                    } else {
                        $nombreRecurso = 'Recurso';
                        if ($primerDetalle) {
                            $nombreRecurso = $primerDetalle->activo->act_nombre ?? ($primerDetalle->aula->aula_nombre ?? 'Recurso');
                        }
                    }
                    
                    $nombreUsuario = $reserva->usuario->nombres ?? ($reserva->usuario->name ?? 'Usuario');
                    $estado = ucfirst($reserva->res_estado ?? ($reserva->estado ?? 'pendiente'));

                    return [
                        'title' => $nombreRecurso . ' - ' . $nombreUsuario,
                        'start' => optional($primerDetalle)->det_re_fecha_ini ?? $reserva->res_fecha_inicio,
                        'end'   => optional($primerDetalle)->det_re_fecha_fin ?? ($reserva->res_fecha_fin ?? optional($primerDetalle)->det_re_fecha_ini),
                        'extendedProps' => [
                            'recurso' => $nombreRecurso,
                            'usuario' => $nombreUsuario,
                            'estado'  => $estado
                        ]
                    ];
                })->toArray();
            @endphp

            <x-agendas.agenda :eventos="$eventosCalendario" />
        </div>

    </div>

    {{-- COMPONENTE DEL MODAL GENERAL CON SU SCRIPT INCLUIDO --}}
    <x-reservas.modal-detalle-reserva :esAdmin="$esAdmin" />

</div>

<script src="{{ asset('js/componentes/filtros-inventario.js') }}"></script>

<script>
    // Función global única para evitar errores de referencia
    function cargarDatosModalReserva(elemento) {
        try {
            // Obtenemos los datos del atributo data-reserva
            const rawData = elemento.getAttribute('data-reserva');
            if (!rawData) return;
            
            const datos = JSON.parse(rawData);
            console.log("Datos cargados correctamente:", datos);

            // 1. Título del modal
            const tituloEl = document.getElementById('modalgeneral-titulo');
            if (tituloEl) tituloEl.innerText = datos.titulo;

            // 2. Función auxiliar segura para inyectar textos
            const setearTexto = (id, valor) => {
                const el = document.getElementById(id);
                if (el) {
                    el.innerText = (valor !== null && valor !== undefined && valor !== '') ? valor : 'N/A';
                }
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

            // 3. Renderizar la lista de recursos múltiples
            const contenedorRecursos = document.getElementById('resumen-bloque-recurso');
            if (contenedorRecursos && datos.recursos) {
                let htmlRecursos = `<h3 class="mb-3"><i class="bi bi-boxes"></i> Recursos Seleccionados (${datos.recursos.length})</h3>`;
                
                datos.recursos.forEach(rec => {
                    htmlRecursos += `
                        <div class="detalle-recurso-item border p-2 mb-2 rounded bg-light">
                            <p class="mb-1"><strong>Recurso:</strong> ${rec.nombre}</p>
                            <p class="mb-1"><strong>Serial:</strong> ${rec.serial}</p>
                            <p class="mb-0"><strong>Marca:</strong> ${rec.marca}</p>
                        </div>
                    `;
                });
                contenedorRecursos.innerHTML = htmlRecursos;
            }

            // 4. Actualizar rutas de los formularios de acción
            const formRechazar = document.getElementById('formRechazarReserva');
            const formAprobar = document.getElementById('formAprobarReserva');
            const formRevertir = document.getElementById('formRevertirReserva');

            if (formRechazar) formRechazar.action = `/reservas/${datos.id}/rechazar`;
            if (formAprobar) formAprobar.action = `/reservas/${datos.id}/aprobar`;
            if (formRevertir) formRevertir.action = `/reservas/${datos.id}/revertir`;

            // 5. Control de visibilidad de botones según el estado
            const bloquePendiente = document.getElementById('bloque-acciones-pendiente');
            const bloqueRevertir = document.getElementById('bloque-acciones-revertir');

            if (bloquePendiente && bloqueRevertir) {
                bloquePendiente.style.setProperty('display', 'none', 'important');
                bloqueRevertir.style.setProperty('display', 'none', 'important');

                const estadoRes = (datos.estado || '').toLowerCase().trim();
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
</script>

@endsection