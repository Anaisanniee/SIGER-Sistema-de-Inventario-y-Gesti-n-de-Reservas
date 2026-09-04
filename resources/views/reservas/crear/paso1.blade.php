@extends('layouts.app')
@section('mostrarPerfil', 'false')
@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'true')

@php
    $user = auth()->user();
    
    $rolSlug = strtolower($user->role->slug ?? $user->rol->slug ?? $user->role ?? $user->rol ?? '');
    $rolId   = $user->role_id ?? $user->rol_id ?? null;

    $esDocente = ($rolSlug === 'docente' || $rolId == 3);
    $esRector  = ($rolSlug === 'rector' || $rolSlug === 'rectora' || $rolId == 2);
    $esSecretario = ($rolSlug === 'secretario' || $rolSlug === 'secretaria' || $rolId == 1);

    $urlRegresar = $esDocente
        ? route('dashboard.docente', ['id' => $user->id])
        : ($esRector
            ? route('dashboard.rectora', ['id' => $user->id])
            : ($esSecretario
                ? route('dashboard.secretaria')
                : route('home')));
@endphp

@section('rutaRegresar', $urlRegresar)

@section('content')
@php
    $tipoRecurso = $tipoRecurso ?? 'activo';
    $recursos = $recursos ?? collect();
    $primerItem = $recursos->first();
    
    // Convertimos de forma segura a objeto si viene como array
    $itemObj = is_array($primerItem) ? (object)$primerItem : $primerItem;
@endphp
<link rel="stylesheet" href="{{ asset('css/components/stepper.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/detalle-recurso.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/reservas.css') }}">

<div class="contenedor-reserva-universal container py-4">

    {{-- 1. COMPONENTE STEPPER --}}
    <x-reservas.stepper paso="1" />

    {{-- 2. REJILLA PRINCIPAL CENTRADA --}}
    <div class="row justify-content-center mt-4">
        <div class="col-lg-8 col-md-10">

            {{-- Bloque Informativo del Recurso --}}
            <div class="tarjeta-reserva-siger p-4 shadow-sm bg-white rounded mb-4">
                <h3 class="fw-bold">Recursos Seleccionados</h3>
                <p class="subtitulo-tarjeta text-muted">Puedes volver atrás para cambiar los elementos</p>

                <x-reservas.detalle-recurso 
                    :tipoRecurso="$tipoRecurso"
                    :recursoNombre="$itemObj->act_nombre ?? ($itemObj->nombre ?? ($itemObj->aula_nombre ?? ($itemObj->descripcion ?? 'Recurso')))"
                    :serial="$itemObj->act_serial ?? ($itemObj->serial ?? ($itemObj->aula_codigo ?? 'Sin Serial'))"
                    :marca="$itemObj->act_marca ?? ($itemObj->marca ?? 'N/A')"
                    :capacidad="$primerRecurso->capacidad ?? ($primerRecurso->aula_capacidad ?? 'N/A')"
                    :recursos="$recursos" 
                />
            </div>

            {{-- Opciones de Decisión y Formulario apuntando a la ruta de lógica --}}
            <form action="{{ route('reservas.paso1.post') }}" method="POST" class="formulario-paso1">
                @csrf
                
                <input type="hidden" name="tipo_recurso" value="{{ $tipoRecurso }}">

                <div class="zona-decision mb-4">
                    <label class="tarjeta-radio d-block mb-2 p-3 border rounded shadow-sm bg-white cursor-pointer">
                        <div class="d-flex align-items-center">
                            <input type="radio" name="confirmacion_recurso" value="si" required checked class="me-3">
                            <div class="radio-contenido d-flex align-items-center">
                                <span class="radio-icono icono-exito text-success fs-4 me-3"><i class="fas fa-check-circle"></i></span>
                                <div>
                                    <strong class="d-block">Sí, es correcto</strong>
                                    <p class="mb-0 text-muted small">Continuar con la asignación de horarios.</p>
                                </div>
                            </div>
                        </div>
                    </label>

                    <label class="tarjeta-radio d-block p-3 border rounded shadow-sm bg-white cursor-pointer">
                        <div class="d-flex align-items-center">
                            <input type="radio" name="confirmacion_recurso" value="no" required class="me-3">
                            <div class="radio-contenido d-flex align-items-center">
                                <span class="radio-icono icono-error text-danger fs-4 me-3"><i class="fas fa-times-circle"></i></span>
                                <div>
                                    <strong class="d-block">No, me equivoqué</strong>
                                    <p class="mb-0 text-muted small">Regresar a la página principal.</p>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>

                <div class="contenedor-botones-paso1 text-end">
                    <x-botones.boton type="submit" class="btn-siger-accion btn btn-primary btn-lg w-100">
                        Siguiente Paso →
                    </x-botones.boton>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection