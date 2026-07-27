@extends('layouts.app')
@section('mostrarPerfil', 'false')
@section('mostrarBusqueda', 'false')
@section('content')
@php
    $recursoId = session('reserva.recurso_id');
    $tipoRecurso = session('reserva.tipo_recurso', 'activo');

    $recurso = null;
    if ($recursoId) {
        if ($tipoRecurso === 'aula') {
            $recurso = \App\Models\AulasModels::find($recursoId);
        } else {
            $recurso = \App\Models\ActivosModels::find($recursoId);
        }
    }

    // Resolución segura del nombre del aula usando el ID numérico guardado en sesión
    $aulaUsoId = session('reserva.aula_uso');
    $nombreAulaUso = 'Salón no especificado';
    if ($aulaUsoId) {
        $aulaObj = \App\Models\AulasModels::find($aulaUsoId);
        if ($aulaObj) {
            $nombreAulaUso = $aulaObj->aula_nombre ?? $aulaObj->nombres ?? ('Salón #' . $aulaUsoId);
        }
    }
@endphp

<link rel="stylesheet" href="{{ asset('css/components/stepper.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/reservas.css') }}">

<div class="contenedor-reserva-universal">
    
    {{-- 1. COMPONENTE STEPPER (Barra de Progreso en Paso 3 - Completo) --}}
    <x-reservas.stepper paso="3" />

    {{-- 2. BLOQUE CENTRAL DE RESUMEN FINAL --}}
    <div class="tarjeta-reserva-siger tarjeta-confirmacion-paso3">
        
        <div class="encabezado-resumen-final">
            <h2><i class="bi bi-file-earmark-check-fill text-verde"></i> Resumen Final de la Reserva</h2>
            <p class="subtitulo-tarjeta">Por favor, verifique todos los datos antes de confirmar la solicitud del recurso.</p>
        </div>

        <div class="grid-resumen-bloques">
            
            {{-- Bloque 1: Datos del Solicitante --}}
            <div class="bloque-resumen-interno">
                <h3><i class="bi bi-person-vcard"></i> Datos del Solicitante</h3>
                <div class="contenido-resumen-item">
                    <p><strong>Nombre:</strong> {{ Auth::user()?->USU_PRIMER_NOMBRE ?? 'Docente' }} {{ Auth::user()?->USU_PRIMER_APELLIDO ?? 'Solicitante' }}</p>
                    <p><strong>Identificación:</strong> {{ Auth::user()?->USU_CEDULA ?? '1.004.234.XXX' }}</p>
                    <p><strong>Correo Electrónico:</strong> {{ Auth::user()?->USU_CORREO ?? 'docente@colegio.edu.co' }}</p>
                </div>
            </div>

            {{-- Bloque 2: Información del Recurso --}}
            <div class="bloque-resumen-interno">
                <h3>
                    @if($tipoRecurso === 'aula')
                        <i class="bi bi-door-open"></i> Datos del Salón
                    @else
                        <i class="bi bi-laptop"></i> Datos del Recurso
                    @endif
                </h3>
                <div class="contenido-resumen-item">
                    <p><strong>Nombre:</strong> {{ $recurso->act_nombre ?? ($recurso->aula_nombre ?? ($recurso->nombres ?? ($tipoRecurso === 'aula' ? 'Laboratorio de Sistemas A' : 'Computador Portátil Dell'))) }}</p>
    
                    @if($tipoRecurso === 'aula')
                        <p><strong>Capacidad:</strong> {{ $recurso->aula_capacidad ?? ($recurso->capacidad ?? '35 Estudiantes') }}</p>
                    @else
                        <p><strong>Serial/Placa:</strong> {{ $recurso->act_serial ?? ($recurso->serial ?? 'DELL-5420-X92') }}</p>
                        <p><strong>Marca:</strong> {{ $recurso->act_marca ?? ($recurso->marca ?? 'Dell Inspiron') }}</p>
                    @endif
                </div>
            </div>

            {{-- Bloque 3: Fecha y Horario --}}
            <div class="bloque-resumen-interno grid-ancho-completo">
                <h3><i class="bi bi-calendar3"></i> Asignación de Tiempos</h3>
                <div class="grid-tiempos-paso3">
                    <div class="tiempo-caja">
                        <span class="tiempo-titulo">Inicio de Reserva</span>
                        <p><i class="bi bi-calendar-event"></i> <strong>Fecha/Hora:</strong> {{ session('reserva.res_fecha_inicio') ?? 'No especificada' }}</p>
                    </div>
                    <div class="tiempo-caja">
                        <span class="tiempo-titulo">Finalización de Reserva</span>
                        <p><i class="bi bi-calendar-check"></i> <strong>Fecha/Hora:</strong> {{ session('reserva.res_fecha_fin') ?? 'No especificada' }}</p>
                    </div>
                </div>
            </div>

            {{-- Bloque Dinámico: Destino del Traslado (Solo para Activos) --}}
                @if($tipoRecurso !== 'aula')
                    <div class="bloque-resumen-interno grid-ancho-completo bloque-ubicacion-paso3">
                        <h3><i class="bi bi-geo-alt-fill"></i> Ubicación de Destino Asignada</h3>
                        <div class="contenido-resumen-item">
                            <p>El recurso será trasladado pedagógicamente para su uso en el siguiente espacio del colegio:</p>
                            <p style="margin-top: 0.75rem;">
                                <strong>Lugar de uso:</strong> 
                                <span class="badge-aula-uso">
                                    <i class="bi bi-pin-map-fill"></i> {{ $nombreAulaUso }}
                                </span>
                            </p>
                        </div>
                    </div>
                @endif

        </div>

        {{-- Formulario Final de Envío --}}
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
</div>
@endsection