@extends('layouts.app')
@section('mostrarPerfil', 'true')
@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'true')

@php
    $user = auth()->user();
    
    // Se obtiene el slug o el id del rol según la estructura del backend
    $rolSlug = strtolower($user->role->slug ?? $user->rol->slug ?? $user->role ?? $user->rol ?? '');
    $rolId   = $user->role_id ?? $user->rol_id ?? null;

    // Docente tiene id = 3 o slug = 'docente'
    $esDocente = ($rolSlug === 'docente' || $rolId == 3);

    $urlRegresar = $esDocente
        ? route('dashboard.docente', ['id' => $user->id])
        : route('dashboard.rectora', ['id' => $user->id]);
@endphp

@section('rutaRegresar', $urlRegresar)
@section('mostrarPerfil', 'false')
@section('content')

<link rel="stylesheet" href="{{ asset('css/pages/perfil.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/form-usuario.css') }}">

<div class="siger-modulo-perfil">
    
    {{-- BARRA SUPERIOR DE CONTEXTO --}}
    <div class="perfil-header-seccion">
        <h2 class="perfil-titulo-principal" style="color: var(--color-principal);">Mi Perfil</h2>
    </div>

    <div class="perfil-layout-contenedor">
        
        {{-- COLUMNA IZQUIERDA: AVATAR, DATOS Y ACCIONES --}}
        <aside class="perfil-columna-izquierda">
            <div class="avatar-wrapper">
                <div class="avatar-circulo" translate="no">
                    @auth
                        {{ strtoupper(substr($usuario->USU_PRIMER_NOMBRE, 0, 1) . substr($usuario->USU_PRIMER_APELLIDO ?? 'U', 0, 1)) }}
                    @else
                        US
                    @endauth
                </div>
            </div>
            
            <h3 class="docente-nombre">{{ $usuario->USU_PRIMER_NOMBRE }} {{ $usuario->USU_PRIMER_APELLIDO }}</h3>
            <p class="docente-email">{{ $usuario->USU_CORREO }}</p>

            <div class="informacion-lateral-lista">
                <div class="item-lateral">
                    <span class="item-titulo">Estado</span>
                    <span class="item-valor">{{ $usuario->USU_ESTADO ?? 'Activo' }}</span>
                </div>
                <div class="item-lateral">
                    <span class="item-titulo">Rol</span>
                    <span class="item-valor" style="text-transform: capitalize;">{{ $usuario->role->name ?? 'Sin Rol' }}</span>
                </div>
                  <div class="item-lateral">
                    <span class="item-titulo">Identificación</span>
                    <span class="item-valor">{{ $usuario->USU_CEDULA ?? 'No especificada' }}</span>
                </div>
                <div class="item-lateral">
                    <span class="item-titulo">Reservas activas</span>
                    <span class="item-valor badge-reserva">{{ $reservasActivasCount ?? 0 }}</span>
                </div>
            </div>

            <div class="acciones-laterales">
                <x-botones.boton 
                    url="{{route('perfil.password.edit')}}" 
                    clase="btn btn-perfil-guardar text-center-link">
                    <i class="fas fa-edit" style="margin-right: 5px;"></i>Cambiar Contraseña
                </x-botones.boton>
            </div>
        </aside>

        {{-- COLUMNA DERECHA --}}
        <main class="perfil-columna-derecha">
            
            {{-- SECCIÓN 1: FORMULARIO DE EDICIÓN --}}
            <div class="formulario-desplegable" id="contenedor-formulario">
                <div class="tarjeta-blanca-datos">
                    <div class="titulo-ficha-datos">
                        <span>Información personal</span>
                    </div>

                    {{-- Formulario envuelto con su ruta de actualización --}}
                    <form action="{{ route('perfil.actualizar') }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('components.formularios.form-usuario', [   
                            'usuario' => $usuario,
                            'modo' => 'perfil'
                        ])
                    </form>
                </div>
            </div>

        </main>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnEditar = document.getElementById('btn-editar-perfil');
        const contenedorForm = document.getElementById('contenedor-formulario');

        if(btnEditar && contenedorForm) {
            btnEditar.addEventListener('click', function() {
                contenedorForm.classList.toggle('activo');
            });
        }
    });
</script>

@endsection