@extends('layouts.app')

@section('mostrarBusqueda', 'false')

@php
    $user = auth()->user();
    
    // Se obtiene el slug o el id del rol según la estructura del backend
    $rolSlug = strtolower($user->role->slug ?? $user->rol->slug ?? $user->role->name ?? $user->rol->nombre ?? '');
    $rolId   = $user->role_id ?? $user->rol_id ?? null;

    // Evaluación de roles
    $esDocente = ($rolSlug === 'docente' || $rolId == 3);
    $esRector  = ($rolSlug === 'rector' || $rolSlug === 'rectora' || $rolId == 2);
    $esSecretario = ($rolSlug === 'secretaria' || $rolSlug === 'secretario' || $rolId == 1);

    // Asignación de ruta de retorno
    if ($esDocente) {
        $urlRegresar = route('dashboard.docente', ['id' => $user->id]);
    } elseif ($esRector) {
        $urlRegresar = route('dashboard.rectora', ['id' => $user->id]);
    } elseif ($esSecretario) {
        $urlRegresar = route('dashboard.secretaria');
    } else {
        $urlRegresar = route('home');
    }
@endphp

@section('rutaRegresar', $urlRegresar)

@section('content')

<link rel="stylesheet" href="{{ asset('css/pages/index-noti.css') }}">

<div class="container my-4">
    <h2 class="mb-4">Mis Notificaciones</h2>

    <div class="list-group">
        @forelse($notificaciones as $notificacion)
            <x-alertas.tarjeta-notificacion :notificacion="$notificacion" />
        @empty
            <div class="alert alert-info text-center">
                No tienes notificaciones pendientes.
            </div>
        @endforelse
    </div>
</div>
@endsection