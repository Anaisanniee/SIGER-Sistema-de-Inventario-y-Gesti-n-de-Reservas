@extends('layouts.app')

@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'true')

@php
    $user = auth()->user();
    $userId  = $user->id ?? $user->usu_id ?? 1;
    
    // Obtenemos el rol de forma segura comprobando string o relación
    $rolSlug = strtolower(optional($user->role)->slug ?? optional($user->rol)->slug ?? $user->role ?? $user->rol ?? '');
    $nombreRol = strtolower(optional($user->role)->name ?? optional($user->rol)->name ?? optional($user->rol)->nombre ?? '');
    $rolId   = $user->role_id ?? $user->rol_id ?? null;

    // Evaluamos el tipo de usuario para la ruta
    if ($rolSlug === 'docente' || $nombreRol === 'docente' || $rolId == 3) {
        $urlRegresar = route('dashboard.docente', ['id' => $userId]);
    } elseif ($rolSlug === 'secretaria' || $nombreRol === 'secretaria' || $rolId == 1) {
        $urlRegresar = route('dashboard.secretaria');
    } else {
        $urlRegresar = route('dashboard.rectora');
    }
@endphp

{{-- Asignación limpia sin problemas de escape --}}
@section('rutaRegresar'){{ $urlRegresar }}@endsection
@section('mostrarPerfil', 'true')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/components/tarjeta-reserva.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/components/resumen-reserva.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/pages/reporte-reservas.css') }}?v={{ time() }}">

@endpush

@section('content')

<div class="siger-modulo-reporte">
    
    <div class="reporte-encabezado">
        <div>
            <h2 class="reporte-titulo">Informe de Reservas</h2>
            <p class="reporte-subtitulo">Revisa las reservas realizadas y su estado.</p>
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
                        $caracteristicaSecundaria = 'Varios elementos seleccionados';
                        
                        $listaRecursos = [];
                        foreach ($detalles as $det) {
                            $listaRecursos[] = optional($det->activo)->act_nombre ?? (optional($det->aula)->aula_nombre ?? 'Elemento');
                        }
                    } else {
                        $activoAsociado = optional($primerDetalle)->activo;
                        $aulaAsociada = optional($primerDetalle)->aula;
                        
                        if (!$aulaAsociada && $primerDetalle && ($primerDetalle->aula_id ?? $primerDetalle->det_re_aula_destino_act ?? null)) {
                            $aId = $primerDetalle->aula_id ?? $primerDetalle->det_re_aula_destino_act;
                            $aulaAsociada = \DB::table('aulas')->where('aula_id', $aId)->first();
                        }

                        if ($activoAsociado && !empty($activoAsociado->act_nombre)) {
                            $nombreRecurso = $activoAsociado->act_nombre;
                            $fotoRecurso = $activoAsociado->act_foto ?? null;
                            $caracteristicaSecundaria = 'Serial: ' . ($activoAsociado->act_serial ?? $activoAsociado->serial ?? 'N/A');
                        } elseif ($aulaAsociada) {
                            $nombreRecurso = $aulaAsociada->aula_nombre ?? $aulaAsociada->nombre ?? 'Aula Asignada';
                            $fotoRecurso = $aulaAsociada->aula_foto ?? $aulaAsociada->foto ?? null;
                            $cap = $aulaAsociada->aula_capacidad ?? $aulaAsociada->capacidad ?? 'N/A';
                            $caracteristicaSecundaria = 'Capacidad: ' . $cap;
                        } else {
                            $nombreRecurso = 'Recurso General';
                            $fotoRecurso = null;
                            $caracteristicaSecundaria = 'N/A';
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

                    // Ubicación
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
                <div class="tarjeta-wrapper recurso-item mb-3 tarjeta-reserva-clicable" 
                     data-id="{{ $reservaId }}" 
                     data-nombre="{{ $nombreRecurso }}" 
                     data-caracteristica="{{ $caracteristicaSecundaria }}"
                     style="cursor: pointer;">
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
    <x-modal id="modalDetalleReserva" title="Nombre" subtitle="Caracteristica" size="lg">
        <div id="contenidoModalDetalleReserva">
            <x-reservas.resumen-reserva :reserva="null" />
        </div>
    </x-modal>

</div>

@push('scripts')
<script>
    window.cargarDatosModal = function (reservaId, nombreRecurso = '', caracteristica = '') {
        const contenidoOculto = document.getElementById(`data-resumen-${reservaId}`);
        const contenedorModal = document.getElementById('contenidoModalDetalleReserva');
        const modalElement = document.getElementById('modalDetalleReserva');

        if (contenidoOculto && contenedorModal && modalElement) {
            contenedorModal.innerHTML = contenidoOculto.innerHTML;

            const tituloModal = modalElement.querySelector('#modal-titulo-dinamico') || modalElement.querySelector('.modal-title');
            const subtituloModal = modalElement.querySelector('#modal-sub-dinamico') || modalElement.querySelector('.modal-subtitle');

            if (tituloModal && nombreRecurso) {
                tituloModal.textContent = nombreRecurso;
            }

            if (subtituloModal && caracteristica) {
                subtituloModal.textContent = caracteristica;
            }

            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modalBootstrap = bootstrap.Modal.getOrCreateInstance(modalElement);
                modalBootstrap.show();
            }
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        const tarjetas = document.querySelectorAll('.tarjeta-reserva-clicable');

        tarjetas.forEach(tarjeta => {
            tarjeta.addEventListener('click', function () {
                const reservaId = this.getAttribute('data-id');
                const nombre = this.getAttribute('data-nombre');
                const caracteristica = this.getAttribute('data-caracteristica');
                
                window.cargarDatosModal(reservaId, nombre, caracteristica);
            });
        });
    });
</script>
@endpush

@endsection