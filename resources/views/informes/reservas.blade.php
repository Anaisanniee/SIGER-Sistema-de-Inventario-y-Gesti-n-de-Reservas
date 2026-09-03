@extends('layouts.app')

@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'true')

@php
    $user = auth()->user();
    $userId  = $user->id ?? 1;
    $rolSlug = strtolower($user->role->slug ?? $user->rol->slug ?? $user->role ?? $user->rol ?? '');
    $rolId   = $user->role_id ?? $user->rol_id ?? null;
    $nombreRol = strtolower($user->role->name ?? $user->rol->name ?? $user->rol->nombre ?? '');

    // Determinamos la ruta de retorno de forma inteligente según el rol del usuario
    if ($rolSlug === 'docente' || $nombreRol === 'docente' || $rolId == 3) {
        $urlRegresar = route('dashboard.docente', ['id' => $user->usu_id ?? $userId]);
    } elseif ($rolSlug === 'secretaria' || $nombreRol === 'secretaria' || $rolId == 1) {
        $urlRegresar = route('dashboard.secretaria');
    } else {
        // Para Rector / Rectora
        $urlRegresar = route('dashboard.rectora');
    }
@endphp

{{-- Inyectamos la ruta dinámicamente según quién tenga la sesión abierta --}}
@section('rutaRegresar', $urlRegresar)
@section('mostrarPerfil', 'true')


@push('styles')
    <link rel="stylesheet" href="{{ asset('css/components/tarjeta-reserva.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/pages/reporte-reservas.css') }}?v={{ time() }}">
@endpush

@section('content')

<div class="siger-modulo-reporte">
    
    <div class="reporte-encabezado">
        <div>
            <h2 class="reporte-titulo">Informe Histórico de Reservas</h2>
            <p class="reporte-subtitulo">Consolidado institucional de solicitudes y préstamos de recursos</p>
        </div>
        
        <x-botones.boton type="button" class="btn btn-siger-imprimir" :url="route('informes.reservas.exportar')">
            <i class="fas fa-file-excel me-2"></i> Exportar a Excel
        </x-botones.boton>
    </div>

    <x-filtros.filtro-fecha-estado action="{{ route('secretaria.informe') }}" />

    <div class="reporte-contenedor-principal">
        <div class="reporte-barra-info">
            <span class="reporte-conteo-registros">
                Registros Encontrados ({{ count($reservas ?? []) }})
            </span>
            <span class="reporte-filtro-etiqueta">Filtro aplicado</span>
        </div>

        <div class="container-tarjetas-vertical">
            @forelse($reservas ?? [] as $reserva)
                @php
                    $reservaId = $reserva->res_id ?? $reserva->id;
                    $detalles = $reserva->detalles;
                    $esMultiple = $detalles->count() > 1;
                    $primerDetalle = $detalles->first();
                    
                    if ($esMultiple) {
                        $fotoFinal = asset('storage/activos/multiple-default.png');
                        $nombreRecurso = 'Reserva Múltiple (' . $detalles->count() . ' elementos)';
                        
                        $listaRecursos = [];
                        foreach ($detalles as $det) {
                            $listaRecursos[] = optional($det->activo)->act_nombre ?? (optional($det->aula)->aula_nombre ?? 'Elemento');
                        }
                    } else {
                        // Verificamos de forma segura si el detalle apunta a un aula o a un activo
                        $activoAsociado = optional($primerDetalle)->activo;
                        $aulaAsociada = optional($primerDetalle)->aula;
                        
                        // Si no viene cargada la relación aula pero existe el ID en el detalle
                        if (!$aulaAsociada && $primerDetalle && ($primerDetalle->aula_id ?? $primerDetalle->det_re_aula_destino_act ?? null)) {
                            $aId = $primerDetalle->aula_id ?? $primerDetalle->det_re_aula_destino_act;
                            $aulaAsociada = \DB::table('aulas')->where('aula_id', $aId)->first();
                        }

                        if ($activoAsociado && !empty($activoAsociado->act_nombre)) {
                            $nombreRecurso = $activoAsociado->act_nombre;
                            $fotoRecurso = $activoAsociado->act_foto ?? null;
                        } elseif ($aulaAsociada) {
                            $nombreRecurso = $aulaAsociada->aula_nombre ?? $aulaAsociada->nombre ?? 'Aula Asignada';
                            $fotoRecurso = $aulaAsociada->aula_foto ?? $aulaAsociada->foto ?? null;
                        } else {
                            $nombreRecurso = 'Recurso General';
                            $fotoRecurso = null;
                        }

                        $fotoFinal = $fotoRecurso ? asset('storage/' . $fotoRecurso) : asset('images/default.png');
                        $listaRecursos = [];
                    }

                    // Solicitante
                    $nombreUsuario = trim((optional($reserva->usuario)->USU_PRIMER_NOMBRE ?? '') . ' ' . (optional($reserva->usuario)->USU_PRIMER_APELLIDO ?? ''));
                    if(empty($nombreUsuario)) $nombreUsuario = 'Solicitante no asignado';

                    // Estado
                    $estadoReserva = ucfirst($reserva->res_estado_reserva ?? $reserva->estado ?? 'Pendiente');

                    // Fecha y Horas
                    $rawFechaIni = optional($primerDetalle)->det_re_fecha_ini ?? $reserva->res_fecha_reserva ?? $reserva->created_at;
                    $rawFechaFin = optional($primerDetalle)->det_re_fecha_fin ?? null;

                    if ($rawFechaIni) {
                        $carbonIni = \Carbon\Carbon::parse($rawFechaIni);
                        $fechaFormateada = $carbonIni->locale('es')->isoFormat('DD [de] MMMM YYYY');
                        $horaInicio = $carbonIni->format('H:i');
                    } else {
                        $fechaFormateada = 'N/A';
                        $horaInicio = 'N/A';
                    }

                    $horaFin = $rawFechaFin ? \Carbon\Carbon::parse($rawFechaFin)->format('H:i') : 'N/A';

                    // Ubicación corregida
                    $ubicacion = 'Sede Principal';
                    if ($primerDetalle) {
                        if (isset($primerDetalle->aula) && $primerDetalle->aula) {
                            $ubicacion = $primerDetalle->aula->aula_nombre ?? 'Aula Asignada';
                        } elseif (optional($primerDetalle->activo)->act_ubicacion) {
                            $ubicacion = $primerDetalle->activo->act_ubicacion;
                        } else {
                            $aulaId = $primerDetalle->det_re_aula_destino_act ?? $primerDetalle->aula_id;
                            if ($aulaId) {
                                $aulaRecord = \DB::table('aulas')->where('aula_id', $aulaId)->first();
                                if ($aulaRecord) {
                                    $ubicacion = $aulaRecord->aula_nombre ?? ('Aula #' . $aulaId);
                                }
                            }
                        }
                    }
                @endphp

                <!-- Tarjeta clicable -->
                <div class="tarjeta-wrapper recurso-item mb-3 tarjeta-reserva-clicable" data-id="{{ $reservaId }}" style="cursor: pointer;">
                    @component('components.tarjetas.tarjeta-reserva', [
                        'id'          => $reservaId,
                        'foto'        => $fotoFinal,
                        'nombre'      => $nombreRecurso,
                        'estado'      => $estadoReserva,
                        'solicitante' => $nombreUsuario,
                        'fecha'       => $fechaFormateada,
                        'horaInicio'  => $horaInicio,
                        'horaFin'     => $horaFin,
                        'ubicacion'   => $ubicacion,
                        'urlGestion'  => '#',
                        'esMultiple'  => $esMultiple,
                        'recursos'    => $listaRecursos
                    ])
                    @endcomponent
                </div>

                <!-- Contenedor oculto con el resumen específico -->
                <div id="data-resumen-{{ $reservaId }}" class="d-none">
                    <x-reservas.resumen-reserva :reserva="$reserva" />
                </div>
            @empty
                <div class="reporte-vacio text-center py-5">
                    <i class="fas fa-folder-open fa-3x mb-3 icono-vacio"></i>
                    <h5 class="titulo-vacio">Sin registros para mostrar</h5>
                    <p class="subtitulo-vacio">No se encontraron reservas que coincidan con los criterios del filtro.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal oficial para mostrar los detalles -->
    <x-modal id="modalDetalleReserva" title="Detalle de la Reserva" size="lg">
        <div id="contenidoModalDetalleReserva">
            <x-reservas.resumen-reserva :reserva="null" />
        </div>
    </x-modal>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tarjetas = document.querySelectorAll('.tarjeta-reserva-clicable');

        tarjetas.forEach(tarjeta => {
            tarjeta.addEventListener('click', function () {
                const reservaId = this.getAttribute('data-id');
                const contenidoOculto = document.getElementById(`data-resumen-${reservaId}`);
                const contenedorModal = document.getElementById('contenidoModalDetalleReserva');

                if (contenidoOculto && contenedorModal) {
                    // Inyectamos el contenido del resumen dentro del modal
                    contenedorModal.innerHTML = contenidoOculto.innerHTML;

                    // Instanciamos y mostramos el modal con Bootstrap 5 de manera segura
                    const modalElement = document.getElementById('modalDetalleReserva');
                    if (modalElement) {
                        const modalBootstrap = bootstrap.Modal.getOrCreateInstance(modalElement);
                        modalBootstrap.show();
                    }
                }
            });
        });
    });
</script>
@endpush

@endsection