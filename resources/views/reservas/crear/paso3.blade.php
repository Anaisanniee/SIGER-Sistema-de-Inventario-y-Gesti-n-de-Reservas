@extends('layouts.app')
@section('mostrarPerfil', 'false')
@section('mostrarBusqueda', 'false')

@section('content')
@php
<<<<<<< HEAD
    // Simulamos las variables para pruebas (o las que mande tu backend desde el controlador / sesión)
    $tipoRecurso = isset($recurso) && is_object($recurso) ? $recurso->tipo : 'aula';
    $recursoNombre = isset($recurso) ? $recurso->nombres : ($tipoRecurso === 'aula' ? 'Laboratorio de Sistemas A' : 'Computador Portátil Dell');
    $capacidad = isset($recurso) ? $recurso->capacidad : '35 Estudiantes';
    $serial = isset($recurso) ? $recurso->serial : 'DELL-5420-X92';
    $marca = isset($recurso) ? $recurso->marca : 'Dell Inspiron';
@endphp

@php
    // Ejemplo de array de recursos (múltiples equipos para pruebas)
    $recursos = $recursos ?? [
        (object)[
            'tipo' => 'activo',
            'nombre' => 'Computador Portátil Dell Inspiron 15',
            'serial' => 'DELL-5420-X92',
            'marca' => 'Dell'
        ],
        (object)[
            'tipo' => 'activo',
            'nombre' => 'Video VideoProyector Epson PowerLite',
            'serial' => 'EPS-880-VP9',
            'marca' => 'Epson'
        ],
        (object)[
            'tipo' => 'activo',
            'nombre' => 'Sistema de Sonido / Cabina Cabina Bluetooth 8" ',
            'serial' => 'JBL-PARTY-04',
            'marca' => 'JBL'
        ],
        (object)[
            'tipo' => 'activo',
            'nombre' => 'Tableta de Dibujo Wacom Intuos',
            'serial' => 'WAC-CTL4100-88',
            'marca' => 'Wacom'
        ],
        (object)[
            'tipo' => 'activo',
            'nombre' => 'Camára Réflex Digital Canon EOS Rebel',
            'serial' => 'CAN-T7-4921',
            'marca' => 'Canon'
        ]
    ];

    // Asignamos para compatibilidad si el backend envía $activosIncluidos
    $activosIncluidos = $activosIncluidos ?? $recursos;
=======
    $recursoId = session('reserva.recurso_id') ?? session('rescurso_id');
    $tipoRecurso = session('reserva.tipo_recurso', 'activo');

    // Buscar recurso único en base de datos si aplica
    $recurso = null;
    if ($recursoId) {
        if ($tipoRecurso === 'aula') {
            $recurso = \App\Models\AulasModels::where('aula_id', $recursoId)->first();
        } else {
            $recurso = \App\Models\ActivosModels::where('act_id', $recursoId)->first();
        }
    }

    // Múltiples recursos o reserva mixta desde la sesión
    $recursosBrutos = session('reserva.recursos_objetos') ?? session('rescursos_objetos', []);
    $recursosColeccion = collect($recursosBrutos);

    // Construir el objeto exacto que el componente <x-reservas.resumen-reserva> exige internamente
    $reservaObj = new \stdClass();
    
    // Datos del usuario autenticado
    $reservaObj->usuario = (object)[
        'nombres' => Auth::user()?->nombres ?? (Auth::user()?->USU_PRIMER_NOMBRE . ' ' . Auth::user()?->USU_PRIMER_APELLIDO ?? 'Docente Solicitante'),
        'identificacion' => Auth::user()?->cedula ?? (Auth::user()?->identificacion ?? (Auth::user()?->USU_CEDULA ?? 'N/A')),
        'email' => Auth::user()?->email ?? (Auth::user()?->USU_CORREO ?? 'correo@colegio.edu.co')
    ];

    // Fechas, horas y motivo extraídos de la sesión
    $reservaObj->res_fecha_inicio = session('reserva.res_fecha_inicio') ?? session('res_fecha_inicio');
    $reservaObj->res_fecha_fin = session('reserva.res_fecha_fin') ?? session('res_fecha_fin');
    $reservaObj->res_motivo = session('reserva.res_motivo') ?? session('res_motivo', 'Desarrollo de clase práctica y actividades pedagógicas programadas.');

    // --- EXTRACCIÓN AUTOMÁTICA DEL AULA EN RESERVA MIXTA O MANUAL ---
    $nombreAulaUso = null;

    // 1. Buscamos primero si el aula viene dentro de los recursos mixtos seleccionados (caso reserva mixta)
    if ($recursosColeccion->isNotEmpty()) {
        foreach ($recursosColeccion as $item) {
            $itemObj = (object)$item;
            // Verificamos si este ítem dentro de la mezcla es un aula
            if ((isset($itemObj->tipo_recurso) && $itemObj->tipo_recurso === 'aula') || isset($itemObj->aula_nombre)) {
                $nombreAulaUso = $itemObj->aula_nombre ?? $itemObj->nombre ?? $itemObj->act_nombre ?? null;
                break;
            }
        }
    }

    // 2. Si no estaba en la colección mixta, revisamos si se guardó en la sesión tradicional de aula de uso
    if (!$nombreAulaUso) {
        $aulaUsoInput = session('reserva.aula_uso');
        if ($aulaUsoInput) {
            if (is_numeric($aulaUsoInput)) {
                // Si es numérico, consultamos directamente su nombre real en la tabla de aulas
                $aulaObj = \App\Models\AulasModels::where('aula_id', $aulaUsoInput)->first();
                if ($aulaObj) {
                    $nombreAulaUso = $aulaObj->aula_nombre;
                }
            } else {
                $nombreAulaUso = $aulaUsoInput;
            }
        }
    }
    // -----------------------------------------------------------------

    // Empaquetar los detalles y recursos para que el componente los renderice sin fallar
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
>>>>>>> origin/backend-Elias
@endphp

<link rel="stylesheet" href="{{ asset('css/components/stepper.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/detalle-recurso.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/reservas.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/resumen-reserva.css') }}">

<div class="contenedor-reserva-universal">
    
    {{-- 1. COMPONENTE STEPPER --}}
    <x-reservas.stepper paso="3" />

<<<<<<< HEAD
    {{-- 2. COMPONENTE DE RESUMEN FINAL --}}
    <x-reservas.resumen-reserva 
        :tipoRecurso="$tipoRecurso"
        :solicitante="Auth::user()->nombres ?? 'Docente Solicitante'"
        :identificacion="Auth::user()->identificacion ?? '1.004.234.XXX'"
        :email="Auth::user()->email ?? 'docente@colegio.edu.co'"
        :recursoNombre="$recursoNombre"
        :capacidad="$capacidad"
        :serial="$serial"
        :marca="$marca"
        :fechaInicio="session('res_fecha_inicio') ?? '2026-07-10'"
        :horaInicio="session('res_hora_inicio') ?? '07:00 AM'"
        :fechaFin="session('res_fecha_fin') ?? '2026-07-10'"
        :horaFin="session('res_hora_fin') ?? '09:30 AM'"
        :aulaUso="session('aula_uso') ?? 'Salón 601'"
        :recursos="$recursos" 
    />

    {{-- 3. FORMULARIO FINAL DE ENVÍO --}}
    <form action="#" method="POST" class="formulario-paso3">
=======
    {{-- 2. COMPONENTE DE RESUMEN FINAL ALIMENTADO POR EL OBJETO --}}
    <x-reservas.resumen-reserva :reserva="$reservaObj" />

    {{-- Mensaje claro indicando el aula de uso seleccionada --}}
    @if(!empty($nombreAulaUso))
        <div class="card my-3 p-3 shadow-sm border-0 bg-light" style="border-left: 4px solid #28a745 !important;">
            <p class="mb-0 text-dark font-weight-medium">
                🏫 El aula de uso seleccionada para esta reserva es: <strong>{{ $nombreAulaUso }}</strong>
            </p>
        </div>
    @endif

    {{-- 3. FORMULARIO FINAL DE ENVÍO --}}
    <form action="{{ route('reservas.paso3.post') }}" method="POST" class="formulario-paso3">
>>>>>>> origin/backend-Elias
        @csrf
        
        <div class="notificacion-alerta-siger margin-top-main">
            <p>⚠️ Al presionar "Confirmar y Guardar", la solicitud se mostrará pendiente para aprobación.</p>
        </div>

        {{-- Botones de Navegación --}}
        <div class="contenedor-botones-paso3">
            <x-botones.boton type="button" class="btn-siger-accion btn btn-azul" onclick="window.history.back();">
<<<<<<< HEAD
                ⬅ Modificar
=======
                ⬅ Modificar Horario
>>>>>>> origin/backend-Elias
            </x-botones.boton>
            
            <x-botones.boton type="submit" class="btn-siger-accion btn">
                Confirmar y Guardar Reserva
            </x-botones.boton>
        </div>
    </form>

</div>
@endsection