@extends('layouts.app')
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

<link rel="stylesheet" href="{{ asset('css/pages/index-noti.css') }}">

<div class="container my-4">
    <h2 class="mb-4">Mis Notificaciones</h2>

    <div class="list-group">
        {{-- AQUÍ ESTÁ EL CAMBIO: Recorremos las notificaciones reales que vienen del controlador --}}
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