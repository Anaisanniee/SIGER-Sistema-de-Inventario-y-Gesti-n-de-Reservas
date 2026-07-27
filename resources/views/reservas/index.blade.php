@extends('layouts.app')

@section('mostrarBusqueda', 'true')
@section('mostrarRegresar', 'true')

@section('content')
<link rel="stylesheet" href="{{ asset('css/pages/reservas.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/tarjeta-reserva.css') }}">

@php
    // Mapeamos las reservas reales de la base de datos al formato visual
    $reservasMapeadas = $reservas->map(function($reserva) {
        $detalle = $reserva->detalles->first();
        
        // Verificamos si la reserva pertenece a un aula
        $esAula = $detalle && $detalle->aula_id && $detalle->aula_id != 1 && optional($detalle->aula)->exists;
        
        if ($esAula) {
            $aula = $detalle->aula;
            
            // Verificamos si el aula tiene una foto guardada, si no, usamos una genérica de aulas
            $fotoRecurso = (!empty($aula->aula_foto)) 
                ? asset(\Illuminate\Support\Facades\Storage::url($aula->aula_foto)) 
                : asset('storage/images/activos/default.jpeg'); // O una imagen genérica válida que tengas en storage

            $nombreRecurso = optional($aula)->aula_nombre ?? 'Aula Reservada';
            $ubicacionRecurso = 'Espacio físico';
        } else {
            $activo = optional($detalle)->activo;
            $fotoRecurso = ($activo && !empty($activo->act_foto)) 
                ? asset(\Illuminate\Support\Facades\Storage::url($activo->act_foto)) 
                : asset('storage/images/activos/default.jpeg');
            $nombreRecurso = optional($activo)->act_nombre ?? 'Recurso Asignado';
            $ubicacionRecurso = optional(optional($detalle)->aula)->aula_nombre ?? 'Aula general';
        }

        return (object)[
            'id'             => $reserva->res_id,
            'recurso_foto'   => $fotoRecurso,
            'recurso_nombre' => $nombreRecurso,
            'estado'         => strtolower($reserva->res_estado_reserva ?? 'pendiente'),
            'usuario_nombre' => optional($reserva->usuario)->name ?? optional($reserva->usuario)->nombres ?? 'Usuario #' . $reserva->usu_id,
            'fecha'          => $detalle && $detalle->det_re_fecha_ini ? \Carbon\Carbon::parse($detalle->det_re_fecha_ini)->format('Y-m-d') : now()->format('Y-m-d'),
            'hora_inicio'    => $detalle && $detalle->det_re_fecha_ini ? \Carbon\Carbon::parse($detalle->det_re_fecha_ini)->format('h:i A') : '--:--',
            'hora_fin'       => $detalle && $detalle->det_re_fecha_fin ? \Carbon\Carbon::parse($detalle->det_re_fecha_fin)->format('h:i A') : '--:--',
            'ubicacion'      => $ubicacionRecurso
        ];
    });
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