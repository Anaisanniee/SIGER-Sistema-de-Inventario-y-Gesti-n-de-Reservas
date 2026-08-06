@extends('layouts.app')

@section('mostrarBusqueda', 'true')
@section('mostrarRegresar', 'true')

@section('content')
<link rel="stylesheet" href="{{ asset('css/pages/reservas.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/tarjeta-reserva.css') }}">

@php
    // Mapeamos las reservas reales agrupando cuando son múltiples
    $reservasMapeadas = collect();

    foreach($reservas as $reserva) {
        $detalles = $reserva->detalles;
        $totalDetalles = $detalles ? $detalles->count() : 0;

        if ($totalDetalles > 1) {
            // CASO 1: Es una reserva múltiple
            $primerDetalle = $detalles->first();
            
            // Buscamos primero si hay un aula_id explícito en los detalles o el destino de activo
            $aulaDestinoId = null;
            foreach($detalles as $det) {
                if (!empty($det->aula_id)) {
                    $aulaDestinoId = $det->aula_id;
                    break;
                }
                if (!empty($det->det_re_aula_destino_act)) {
                    $aulaDestinoId = $det->det_re_aula_destino_act;
                    break;
                }
            }
            
            $aulaDestinoObj = !empty($aulaDestinoId) ? \App\Models\AulasModels::find($aulaDestinoId) : null;
            $ubicacionRecurso = $aulaDestinoObj->aula_nombre ?? $aulaDestinoObj->nombres ?? 'Espacio institucional';

            // Imagen por defecto específica para reservas múltiples (puedes cambiarla por la ruta de tu asset)
            $fotoRecurso = asset('storage/images/activos/multi-default.png'); 
            // Si no tienes una imagen específica, puedes usar un icono o una general: asset('storage/images/activos/default.jpeg')

            $reservasMapeadas->push((object)[
                'id'             => $reserva->res_id,
                'recurso_foto'   => $fotoRecurso,
                'recurso_nombre' => 'Reserva Múltiple (' . $totalDetalles . ' elementos)',
                'estado'         => strtolower($reserva->res_estado_reserva ?? 'pendiente'),
                'usuario_nombre' => optional($reserva->usuario)->name ?? optional($reserva->usuario)->nombres ?? 'Usuario #' . $reserva->usu_id,
                'fecha'          => $primerDetalle->det_re_fecha_ini ? \Carbon\Carbon::parse($primerDetalle->det_re_fecha_ini)->format('Y-m-d') : now()->format('Y-m-d'),
                'hora_inicio'    => $primerDetalle->det_re_fecha_ini ? \Carbon\Carbon::parse($primerDetalle->det_re_fecha_ini)->format('h:i A') : '--:--',
                'hora_fin'       => $primerDetalle->det_re_fecha_fin ? \Carbon\Carbon::parse($primerDetalle->det_re_fecha_fin)->format('h:i A') : '--:--',
                'ubicacion'      => $ubicacionRecurso
            ]);

        } elseif ($totalDetalles === 1) {
            // CASO 2: Es un único elemento
            $detalle = $detalles->first();
            $actId = $detalle->act_id;
            $aulaId = $detalle->aula_id;
            $aulaDestinoAct = $detalle->det_re_aula_destino_act;

            $aulaObj = !empty($aulaId) ? \App\Models\AulasModels::find($aulaId) : null;
            $esReservaDeAulaDirecta = !is_null($aulaObj);

            if ($esReservaDeAulaDirecta) {
                $fotoRecurso = (!empty($aulaObj->aula_foto)) 
                    ? asset(\Illuminate\Support\Facades\Storage::url($aulaObj->aula_foto)) 
                    : asset('storage/images/activos/default.jpeg');
                $nombreRecurso = $aulaObj->aula_nombre ?? $aulaObj->nombres ?? 'Aula Reservada';
                $ubicacionRecurso = 'Espacio físico';
            } else {
                $activo = !empty($actId) ? \App\Models\ActivosModels::find($actId) : null;
                $fotoRecurso = ($activo && !empty($activo->act_foto)) 
                    ? asset(\Illuminate\Support\Facades\Storage::url($activo->act_foto)) 
                    : asset('storage/images/activos/default.jpeg');
                $nombreRecurso = optional($activo)->act_nombre ?? 'Recurso Asignado';
                
                $idUstedDestino = $aulaDestinoAct ?? $detalle->aula_id;
                $aulaDestinoObj = !empty($idUstedDestino) ? \App\Models\AulasModels::find($idUstedDestino) : null;
                $ubicacionRecurso = $aulaDestinoObj->aula_nombre ?? $aulaDestinoObj->nombres ?? 'Aula general';
            }

            $reservasMapeadas->push((object)[
                'id'             => $reserva->res_id,
                'recurso_foto'   => $fotoRecurso,
                'recurso_nombre' => $nombreRecurso,
                'estado'         => strtolower($reserva->res_estado_reserva ?? 'pendiente'),
                'usuario_nombre' => optional($reserva->usuario)->name ?? optional($reserva->usuario)->nombres ?? 'Usuario #' . $reserva->usu_id,
                'fecha'          => $detalle->det_re_fecha_ini ? \Carbon\Carbon::parse($detalle->det_re_fecha_ini)->format('Y-m-d') : now()->format('Y-m-d'),
                'hora_inicio'    => $detalle->det_re_fecha_ini ? \Carbon\Carbon::parse($detalle->det_re_fecha_ini)->format('h:i A') : '--:--',
                'hora_fin'       => $detalle->det_re_fecha_fin ? \Carbon\Carbon::parse($detalle->det_re_fecha_fin)->format('h:i A') : '--:--',
                'ubicacion'      => $ubicacionRecurso
            ]);
        }
    }
@endphp

<div class="panel-administracion-contenedor">
    
    <!-- CABECERA DEL PANEL -->
    <div class="cabecera-panel">
        <div class="texto-cabecera">
            <h2 class="titulo-pagina"><i class="fas fa-calendar-alt"></i> Solicitudes de equipos y aulas</h2>
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
        
        <!-- COLUMNA IZQUIERDA: LISTADO DE TARJETAS -->
        <div class="columna-solicitudes">
            <div class="container-tarjetas-vertical">
                @forelse($reservasMapeadas as $reserva)
                    @php
                        $tagsReserva = ['reserva', strtolower($reserva->estado)];
                        $strTagsReserva = implode(' ', $tagsReserva);
                    @endphp
                    
                    <div class="tarjeta-wrapper recurso-item" data-tags="{{ $strTagsReserva }}">
                        @component('components.tarjetas.tarjeta-reserva', [
                            'id'          => $reserva->id,
                            'foto'        => $reserva->recurso_foto,
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
                @empty
                    <div style="text-align: center; padding: 40px; color: var(--color-texto-secundario);">
                        <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 10px; opacity: 0.5;"></i>
                        <p>No hay solicitudes de reservas registradas en este momento.</p>
                    </div>
                @endforelse
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