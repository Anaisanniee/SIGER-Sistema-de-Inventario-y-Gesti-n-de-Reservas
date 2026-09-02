@extends('layouts.app')
@section('mostrarPerfil', 'false')
@section('mostrarBusqueda', 'false')
@section('rutaRegresar', route('reservas.paso2')) 

@section('content')
@php
    $recursoId = session('reserva.recurso_id') ?? session('rescurso_id');
    $tipoRecurso = session('reserva.tipo_recurso', 'activo');

    $recurso = null;
    if ($recursoId) {
        if ($tipoRecurso === 'aula') {
            $recurso = \App\Models\AulasModels::where('aula_id', $recursoId)->first();
        } else {
            $recurso = \App\Models\ActivosModels::where('act_id', $recursoId)->first();
        }
    }

    $recursosBrutos = session('reserva.recursos_objetos') ?? session('rescursos_objetos', []);
    $recursosColeccion = collect($recursosBrutos);

    $reservaObj = new \stdClass();
    
    $reservaObj->usuario = (object)[
        'nombres' => Auth::user()?->nombres ?? (Auth::user()?->USU_PRIMER_NOMBRE . ' ' . Auth::user()?->USU_PRIMER_APELLIDO ?? 'Docente Solicitante'),
        'identificacion' => Auth::user()?->cedula ?? (Auth::user()?->identificacion ?? (Auth::user()?->USU_CEDULA ?? 'N/A')),
        'email' => Auth::user()?->email ?? (Auth::user()?->USU_CORREO ?? 'correo@colegio.edu.co')
    ];

    $reservaObj->res_fecha_inicio = session('reserva.res_fecha_inicio') ?? session('res_fecha_inicio');
    $reservaObj->res_fecha_fin = session('reserva.res_fecha_fin') ?? session('res_fecha_fin');
    $reservaObj->res_motivo = session('reserva.res_motivo') ?? session('res_motivo', 'Desarrollo de clase práctica y actividades pedagógicas programadas.');

    $nombreAulaUso = null;

    if ($recursosColeccion->isNotEmpty()) {
        foreach ($recursosColeccion as $item) {
            $itemObj = (object)$item;
            if ((isset($itemObj->tipo_recurso) && $itemObj->tipo_recurso === 'aula') || isset($itemObj->aula_nombre)) {
                $nombreAulaUso = $itemObj->aula_nombre ?? $itemObj->nombre ?? $itemObj->act_nombre ?? null;
                break;
            }
        }
    }

    if (!$nombreAulaUso) {
        $aulaUsoInput = session('reserva.aula_uso');
        if ($aulaUsoInput) {
            if (is_numeric($aulaUsoInput)) {
                $aulaObj = \App\Models\AulasModels::where('aula_id', $aulaUsoInput)->first();
                if ($aulaObj) {
                    $nombreAulaUso = $aulaObj->aula_nombre;
                }
            } else {
                $nombreAulaUso = $aulaUsoInput;
            }
        }
    }

    $detallesArray = [];

    if ($recursosColeccion->isNotEmpty()) {
        foreach ($recursosColeccion as $item) {
            $itemObj = (object)$item;
            
            $esAulaItem = isset($itemObj->tipo_recurso) && $itemObj->tipo_recurso === 'aula' 
                        || isset($itemObj->aula_nombre);

            $detallesArray[] = (object)[
                'act_id' => $itemObj->act_id ?? $itemObj->id ?? null,
                'activo' => !$esAulaItem ? (object)[
                    'act_nombre' => $itemObj->act_nombre ?? $itemObj->nombre ?? 'Recurso',
                    'act_serial' => $itemObj->act_serial ?? $itemObj->serial ?? 'Sin Serial',
                    'act_marca'  => $itemObj->act_marca ?? $itemObj->marca ?? 'N/A'
                ] : null,
                'aula' => $esAulaItem ? (object)[
                    'aula_nombre' => $itemObj->aula_nombre ?? $itemObj->nombre ?? $itemObj->act_nombre ?? 'Salón'
                ] : null
            ];
        }
    } elseif ($recurso) {
        $esAulaUnica = ($tipoRecurso === 'aula');

        $detallesArray[] = (object)[
            'act_id' => $recurso->act_id ?? null,
            'activo' => !$esAulaUnica ? (object)[
                'act_nombre' => $recurso->act_nombre ?? $recurso->nombres ?? 'Recurso',
                    'act_serial' => $recurso->act_serial ?? $recurso->serial ?? 'Sin Serial',
                    'act_marca'  => $recurso->act_marca ?? $recurso->marca ?? 'N/A'
            ] : null,
            'aula' => $esAulaUnica ? (object)[
                'aula_nombre' => $recurso->aula_nombre ?? $recurso->nombres ?? 'Salón'
            ] : null
        ];
    }

    $reservaObj->detalles = collect($detallesArray);
@endphp

<link rel="stylesheet" href="{{ asset('css/components/stepper.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/detalle-recurso.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/reservas.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/resumen-reserva.css') }}">

<div class="contenedor-reserva-universal">
    
    <x-reservas.stepper paso="3" />

    <x-reservas.resumen-reserva :reserva="$reservaObj" />

    @if(!empty($nombreAulaUso))
        <div class="card my-3 p-3 shadow-sm border-0 bg-light" style="border-left: 4px solid #28a745 !important;">
            <p class="mb-0 text-dark font-weight-medium">
                🏫 El aula de uso seleccionada para esta reserva es: <strong>{{ $nombreAulaUso }}</strong>
            </p>
        </div>
    @endif

    <form action="{{ route('reservas.paso3.post') }}" method="POST" class="formulario-paso3">
        @csrf
        
        <div class="notificacion-alerta-siger margin-top-main">
            <p>⚠️ Al presionar "Confirmar y Guardar", la solicitud se mostrará pendiente para aprobación.</p>
        </div>

        <div class="contenedor-botones-paso3">
            <x-botones.boton type="button" class="btn-siger-accion btn btn-azul" onclick="window.history.back();">
                ⬅ Modificar Horario
            </x-botones.boton>
            
            <x-botones.boton type="submit" class="btn-siger-accion btn">
                Confirmar y Guardar Reserva
            </x-botones.boton>
        </div>
    </form>

</div>
@endsection