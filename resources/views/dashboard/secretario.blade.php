@extends('layouts.app') 

@section('content')
@section('mostrarRegresar', 'false')

<link rel="stylesheet" href="{{ asset('css/pages/dashboard-secretario.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/tarjeta-reserva.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/resumen-reserva.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/detalle-recurso.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/reservas.css') }}">

@php
    $esAdmin = Auth::user()->esAdmin ?? true;

<<<<<<< HEAD
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
            'estado' => 'pendiente',
            'usuario_nombre' => 'Docente María Alejandra',
            'fecha' => '2026-07-13',
            'hora_inicio' => '10:00 AM',
            'hora_fin' => '12:00 PM',
            'ubicacion' => 'Laboratorio de Sistemas'
        ]
    ];
=======
    if (!isset($reservas) && !isset($reservasSimuladas)) {
        $fuenteReservas = \App\Models\ReservasModels::with(['detalles.activo', 'detalles.aula', 'usuario'])->get();
    } else {
        $fuenteReservas = $reservas ?? $reservasSimuladas;
    }

    $procesarReservaDashboard = function($reserva) {
        $detalles = $reserva->detalles ?? collect();
        $totalDetalles = count($detalles);
        $esMultiple = $totalDetalles > 1;
        $primerDetalle = $totalDetalles > 0 ? $detalles->first() : null;

        $nombreRecurso = 'Recurso General';
        $ubicacion = 'N/A';
        $fotoRecurso = asset('storage/images/activos/default.jpeg');
        $serialRecurso = 'N/A';
        $marcaRecurso = 'N/A';

        // Extracción de la ubicación global de la reserva
        if ($primerDetalle) {
            if (!empty($primerDetalle->aula)) {
                $ubicacion = $primerDetalle->aula->aula_nombre ?? ($primerDetalle->aula->nombre ?? 'N/A');
            } elseif (!empty($primerDetalle->aula_id)) {
                $aulaObj = \App\Models\AulasModels::find($primerDetalle->aula_id);
                $ubicacion = $aulaObj ? ($aulaObj->aula_nombre ?? ($aulaObj->nombre ?? 'N/A')) : 'N/A';
            }
        }

        if ($esMultiple) {
            $nombreRecurso = "Reserva Múltiple ({$totalDetalles} ítems)";
            $fotoRecurso = asset('storage/activos/multiple-default.png');
        } elseif ($primerDetalle) {
            $activoObj = $primerDetalle->activo ?? null;
            if ($activoObj) {
                $nombreRecurso = $activoObj->act_nombre ?? ($activoObj->nombre ?? 'Activo sin nombre');
                $serialRecurso = $activoObj->act_serial ?? ($activoObj->serial ?? 'N/A');
                $marcaRecurso = $activoObj->act_marca ?? ($activoObj->marca ?? 'N/A');
                $rutaFoto = $activoObj->act_foto ?? ($activoObj->foto ?? null);
                if ($rutaFoto) {
                    $fotoRecurso = str_starts_with($rutaFoto, 'http') ? $rutaFoto : asset('storage/' . $rutaFoto);
                }
            }
        }

        $usuarioObj = $reserva->usuario ?? \App\Models\User::find($reserva->usu_id);
        $nombreUsuario = $usuarioObj->nombres ?? ($usuarioObj->name ?? 'Usuario Institucional');

        $fechaCruda = $primerDetalle->det_re_fecha_ini ?? $reserva->res_fecha_creacion;
        $fechaFormateada = $fechaCruda ? \Carbon\Carbon::parse($fechaCruda)->format('d \d\e F Y') : 'Fecha no definida';

        $horaInicio = $primerDetalle && $primerDetalle->det_re_fecha_ini 
            ? \Carbon\Carbon::parse($primerDetalle->det_re_fecha_ini)->format('h:i A') 
            : '08:00 AM';
            
        $horaFin = $primerDetalle && $primerDetalle->det_re_fecha_fin 
            ? \Carbon\Carbon::parse($primerDetalle->det_re_fecha_fin)->format('h:i A') 
            : '10:00 AM';

        // Mapeo inteligente para reservas mixtas (Soporta Activos y Aulas por igual)
        // Mapeo estricto: Primero busca si es un Activo, y solo si no existe, busca si es un Aula
        $listaRecursosModal = $detalles->map(function($det) {
            $nombreItem = 'Recurso / Ítem';
            $serialItem = 'N/A';
            $marcaItem = 'N/A';
            $fotoItem = asset('storage/activos/multiple-default.png');

            // 1. PRIMERO intentamos resolver si es un Activo (portátil, equipo, etc.)
            $act = $det->activo ?? null;
            $actId = $det->act_id ?? ($det->activo_id ?? null);
            if (!$act && $actId) {
                $act = \App\Models\ActivosModels::where('act_id', $actId)->first();
            }

            if ($act) {
                $nombreItem = $act->act_nombre ?? ($act->nombre ?? 'Activo');
                $serialItem = 'Número de serie: ' . ($act->act_serial ?? ($act->serial ?? 'N/A'));
                $marcaItem = 'Marca: ' . ($act->act_marca ?? ($act->marca ?? 'N/A'));
                $rutaFoto = $act->act_foto ?? ($act->foto ?? null);
                if ($rutaFoto) {
                    $fotoItem = str_starts_with($rutaFoto, 'http') ? $rutaFoto : asset('storage/' . $rutaFoto);
                }
            } else {
                // 2. SI NO ES UN ACTIVO, evaluamos si es un Aula o Espacio físico
                $aulaObj = $det->aula ?? null;
                $aulaId = $det->aula_id ?? ($det->det_re_aula_destino_act ?? null);
                if (!$aulaObj && $aulaId) {
                    $aulaObj = \App\Models\AulasModels::find($aulaId);
                }

                if ($aulaObj) {
                    $nombreItem = $aulaObj->aula_nombre ?? ($aulaObj->nombre ?? 'Aula Institucional');
                    $serialItem = ''; 
                    $marcaItem = 'Salón / Aula';
                }
            }

            return [
                'nombre' => $nombreItem,
                'serial' => $serialItem,
                'marca'  => $marcaItem,
                'foto'   => $fotoItem
            ];
        })->toArray();

        $estadoReserva = strtolower($reserva->res_estado_reserva ?? 'pendiente');

        $datosModal = [
            "id" => $reserva->res_id,
            "estado" => $estadoReserva,
            "titulo" => "Detalle de Reserva #" . $reserva->res_id,
            "solicitante" => $nombreUsuario,
            "identificacion" => $usuarioObj->identificacion ?? ($usuarioObj->cedula ?? "N/A"),
            "email" => $usuarioObj->email ?? "No disponible",
            "motivo" => $reserva->res_motivo ?? "Sin motivo especificado.",
            "fechaInicio" => $fechaCruda ? \Carbon\Carbon::parse($fechaCruda)->format("Y-m-d") : date('Y-m-d'),
            "horaInicio" => $horaInicio,
            "fechaFin" => $fechaCruda ? \Carbon\Carbon::parse($fechaCruda)->format("Y-m-d") : date('Y-m-d'),
            "horaFin" => $horaFin,
            "aula" => $ubicacion,          
            "ubicacion" => $ubicacion,     
            "recursos" => $listaRecursosModal
        ];

        return [
            'base64' => base64_encode(json_encode($datosModal)),
            'id' => $reserva->res_id,
            'foto' => $fotoRecurso,
            'nombre' => $nombreRecurso,
            'estado' => $estadoReserva,
            'solicitante' => $nombreUsuario,
            'fecha' => $fechaFormateada,
            'horaInicio' => $horaInicio,
            'horaFin' => $horaFin,
            'ubicacion' => $ubicacion
        ];
    };

    $pendientes = collect($fuenteReservas)->filter(function($r) {
        $estado = $r->res_estado_reserva ?? '';
        return strtolower(trim($estado)) === 'pendiente';
    });
>>>>>>> origin/backend-Elias
@endphp

{{--- 1. TARJETA DE BIENVENIDA ---}}
@include('components.tarjetas.tarjeta-bienvenido', [
    'titulo' => 'Panel de Control - SIGER',   
    'descripcion' => 'Sistema institucional de inventario, activos y gestión de reservas en tiempo real.'
])

{{--- 2. SECCIÓN SUPERIOR: ACCESOS Y ALERTAS ---}}
<div class="dashboard-grid">
    
<<<<<<< HEAD
    <!-- COLUMNA 1: ACCESOS RÁPIDOS -->
    <div class="dashboard-columna">
        <h3 class="dashboard-subtitulo">Módulos Disponibles</h3>
        <div class="contenedor-accesos">
            
            <a href="{{ url('/reservas/gestion') }}" class="tarjeta-acceso-rapido acceso-reservas">
                <div class="acceso-icono">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="acceso-texto">
                    <h4>Gestión de Reservas</h4>
                    <p>Revisa solicitudes, aprueba, rechaza y controla las agendas del día.</p>
                </div>
                <i class="fas fa-chevron-right flecha-acceso"></i>
            </a>

            <a href="{{ url('/inventario') }}" class="tarjeta-acceso-rapido acceso-inventario">
                <div class="acceso-icono">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="acceso-texto">
                    <h4>Gestión de Inventario</h4>
                    <p>Controla las aulas, equipos tecnológicos y el estado de los activos.</p>
                </div>
                <i class="fas fa-chevron-right flecha-acceso"></i>
            </a>

        </div>
    </div>

    <!-- COLUMNA 2: ALERTAS DEL SISTEMA -->
    <div class="dashboard-columna">
        <h3 class="dashboard-subtitulo">Alertas del Sistema</h3>
        
        <div class="contenedor-alertas" id="contenedor-alertas-siger"> <!---COLOCAR NOTIFICACIONES CUANDOS E ESTEN LISTA EN LOS CONTROLLERS--->
            <x-alertas.notificacion tipo="peligro" titulo="Entrega Retrasada">
                El <strong>Computador Dell Inspiron</strong> debió devolverse a las 10:00 AM.
            </x-alertas.notificacion>

            <x-alertas.notificacion tipo="advertencia" titulo="Mantenimiento">
                El Aula 101 reporta fallas en la red.
            </x-alertas.notificacion>
        </div>
    </div>

=======
    <div class="dashboard-columna">
        <h3 class="dashboard-subtitulo">Módulos Disponibles</h3>
        <div class="contenedor-accesos">
            <x-tarjetas.tarjeta-acceso-rapido :href="url('/secretaria/reservas')" icono="fas fa-calendar-check" claseAcceso="acceso-reservas" titulo="Gestión de Reservas" descripcion="Revisa solicitudes, aprueba, rechaza y controla las agendas del día."/>
            <x-tarjetas.tarjeta-acceso-rapido :href="url('/inventario')" icono="fas fa-boxes" claseAcceso="acceso-inventario" titulo="Gestión de Inventario" descripcion="Controla las aulas, equipos tecnológicos y el estado de los activos."/>
        </div>
    </div>

    <div class="dashboard-columna">
        <h3 class="dashboard-subtitulo">Alertas del Sistema</h3>
        <div class="contenedor-alertas" id="contenedor-alertas-siger">
            <x-alertas.notificacion tipo="peligro" titulo="Entrega Retrasada">El <strong>Computador Dell Inspiron</strong> debió devolverse a las 10:00 AM.</x-alertas.notificacion>
            <x-alertas.notificacion tipo="advertencia" titulo="Mantenimiento">El Aula 101 reporta fallas en la red.</x-alertas.notificacion>
        </div>
    </div>
>>>>>>> origin/backend-Elias
</div>

{{--- 3. SECCIÓN INFERIOR: PENDIENTES ---}}
<div class="dashboard-pendientes-seccion">
    <div class="pendientes-header">
<<<<<<< HEAD
        <h3 class="dashboard-subtitulo">
            <i class="fas fa-clock"></i> Solicitudes Pendientes
        </h3>
        <a href="{{ url('/reservas/gestion') }}" class="btn-ver-todo">Ver toda la gestión</a>
    </div>

    <div class="container-tarjetas-vertical">
        @foreach($reservasSimuladas as $reserva)
            @if(strtolower($reserva->estado) === 'pendiente')
                <div class="tarjeta-dashboard-link">
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
                        'urlGestion'  => '#'
                    ])
                    @endcomponent
                </div>
            @endif
        @endforeach
    </div>
</div>

{{-- COMPONENTE DEL MODAL GENERAL CON SU SCRIPT INCLUIDO --}}
=======
        <h3 class="dashboard-subtitulo"><i class="fas fa-clock"></i> Solicitudes Pendientes</h3>
        <a href="{{ url('/secretaria/reservas') }}" class="btn-ver-todo">Ver toda la gestión</a>
    </div>

    <div class="container-tarjetas-vertical">
        @forelse($pendientes as $reserva)
            @php $info = $procesarReservaDashboard($reserva); @endphp
            <div class="tarjeta-dashboard-link abrir-modal" data-modal="{{ $info['base64'] }}" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalgeneral">
                @component('components.tarjetas.tarjeta-reserva', [
                    'id' => $info['id'], 'foto' => $info['foto'], 'nombre' => $info['nombre'], 'estado' => $info['estado'],
                    'solicitante' => $info['solicitante'], 'fecha' => $info['fecha'], 'horaInicio' => $info['horaInicio'],
                    'horaFin' => $info['horaFin'], 'ubicacion' => $info['ubicacion'], 'urlGestion' => '#'
                ]) @endcomponent
            </div>
        @empty
            <div class="text-center py-4 bg-white rounded shadow-sm"><p class="text-muted mb-0">No hay solicitudes pendientes.</p></div>
        @endforelse
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.abrirModalDetalle = function(datos) {
            if (!datos) return;

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
            
            const valorAulaUbicacion = datos.aula || datos.ubicacion || 'N/A';
            ['resumen-aula-uso', 'resumen-aula', 'resumen-ubicacion', 'modal-aula', 'modal-ubicacion', 'detalle-aula'].forEach(id => {
                setearTexto(id, valorAulaUbicacion);
            });

            document.querySelectorAll('#modalgeneral .resumen-aula, #modalgeneral .resumen-ubicacion, #modalgeneral [id*="aula"], #modalgeneral [id*="ubicacion"]').forEach(function(el) {
                if(el && el.tagName.toLowerCase() !== 'input' && el.tagName.toLowerCase() !== 'select') {
                    el.innerText = valorAulaUbicacion;
                }
            });

            // Renderizar la lista de recursos (Activos y Aulas mixtas)
            const contenedorRecursos = document.getElementById('resumen-bloque-recurso');
            if (contenedorRecursos && datos.recursos) {
                let totalRecursos = datos.recursos.length;
                let htmlRecursos = `
                    <h3 class="mb-3" style="font-size: 1.1rem; font-weight: 600; color: #212529;">Recursos Seleccionados (${totalRecursos})</h3>
                    <div id="contenedor-acordeon-recursos" class="border rounded p-3" style="border-color: #dee2e6 !important; transition: border-color 0.2s ease;">
                        <div style="cursor: pointer; display: flex; justify-content: space-between; align-items: center;" onclick="toggleAcordeonRecursos()">
                            <span style="font-weight: 600; color: #212529;">Lista de recursos (${totalRecursos})</span>
                            <i id="icono-acordeon-flecha" class="fas fa-chevron-down" style="transition: transform 0.3s ease;"></i>
                        </div>
                        <div id="acordeon-recursos-body" class="mt-3" style="display: none;">
                `;
                
                datos.recursos.forEach(rec => {
                    htmlRecursos += `
                        <div class="border p-3 mb-2 rounded" style="background-color: #ffffff; border-color: #dee2e6 !important; transition: background-color 0.2s ease;"
                             onmouseover="this.style.backgroundColor='#d1e7dd'; this.style.borderColor='#badbcc'" 
                             onmouseout="this.style.backgroundColor='#ffffff'; this.style.borderColor='#dee2e6'">
                            <p class="mb-1" style="font-weight: 600; color: #212529;">${rec.nombre}</p>
                            <p class="mb-1 text-muted" style="font-size: 0.9rem;">${rec.serial}</p>
                            <p class="mb-0 text-muted" style="font-size: 0.9rem;">${rec.marca}</p>
                        </div>
                    `;
                });
                
                htmlRecursos += `</div></div>`;
                contenedorRecursos.innerHTML = htmlRecursos;
            }

            const urlRechazar = `/secretaria/reservas/${datos.id}/rechazar`;
            const urlAprobar = `/secretaria/reservas/${datos.id}/aprobar`;
            const urlRevertir = `/secretaria/reservas/${datos.id}/revertir`;

            ['formRechazar', 'formRechazarReserva'].forEach(id => {
                const f = document.getElementById(id);
                if (f) f.action = urlRechazar;
            });
            ['formAprobar', 'formAprobarReserva'].forEach(id => {
                const f = document.getElementById(id);
                if (f) f.action = urlAprobar;
            });
            ['formRevertir', 'formRevertirReserva'].forEach(id => {
                const f = document.getElementById(id);
                if (f) f.action = urlRevertir;
            });

            const bloquePendiente = document.getElementById('bloque-acciones-pendiente');
            const bloqueRevertir = document.getElementById('bloque-acciones-revertir');

            if (bloquePendiente && bloqueRevertir) {
                bloquePendiente.style.setProperty('display', 'none', 'important');
                bloqueRevertir.style.setProperty('display', 'none', 'important');

                const estado = (datos.estado || '').toLowerCase().trim();
                if (estado === 'pendiente') {
                    bloquePendiente.style.setProperty('display', 'flex', 'important');
                } else if (['aprobada', 'rechazada', 'aprobado', 'rechazado'].includes(estado)) {
                    bloqueRevertir.style.setProperty('display', 'flex', 'important');
                }
            }
        };

        window.toggleAcordeonRecursos = function() {
            const body = document.getElementById('acordeon-recursos-body');
            const flecha = document.getElementById('icono-acordeon-flecha');
            const contenedor = document.getElementById('contenedor-acordeon-recursos');
            
            if (body) {
                const isHidden = body.style.display === 'none';
                body.style.display = isHidden ? 'block' : 'none';
                if (flecha) flecha.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
                if (contenedor) contenedor.style.borderColor = isHidden ? '#198754' : '#dee2e6';
            }
        };

        document.querySelectorAll('.abrir-modal').forEach(function (element) {
            element.addEventListener('click', function (e) {
                let modalData = this.getAttribute('data-modal');
                if (modalData) {
                    try {
                        let decodedJson = atob(modalData);
                        let parsedData = JSON.parse(decodedJson);
                        window.abrirModalDetalle(parsedData);
                    } catch (err) { 
                        console.error("Error al decodificar:", err); 
                    }
                }
            });
        });
    });
</script>

>>>>>>> origin/backend-Elias
<x-reservas.modal-detalle-reserva :esAdmin="$esAdmin" />

@endsection