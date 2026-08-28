@extends('layouts.app')

@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'true')
<<<<<<< HEAD
@section('rutaRegresar', auth()->user()->role === 'docente' 
    ? route('dashboard.docente', ['id' => auth()->id()]) 
    : route('dashboard.rectora', ['id' => auth()->id()]))@section('mostrarPerfil', 'false')
=======
@section('rutaRegresar', url('/dashboard'))

@section('mostrarPerfil', 'false')
>>>>>>> origin/backend-Elias
@section('content')

<link rel="stylesheet" href="{{ asset('css/pages/perfil.css') }}">

<div class="siger-modulo-perfil">
    
    {{-- BARRA SUPERIOR DE CONTEXTO --}}
    <div class="perfil-header-seccion">
<<<<<<< HEAD
        <h2 class="perfil-titulo-principal" style="color: var(--color-principal);">Mi Perfil</h2>

=======
        <h2 class="perfil-titulo-principal">Mi perfil</h2>
        <p class="perfil-subtitulo">Gestiona tu cuenta e información personal en SIGER</p>
>>>>>>> origin/backend-Elias
    </div>

    <div class="perfil-layout-contenedor">
        
        {{-- COLUMNA IZQUIERDA: AVATAR, DATOS Y ACCIONES --}}
        <aside class="perfil-columna-izquierda">
            <div class="avatar-wrapper">
                <div class="avatar-circulo">
                    @auth
<<<<<<< HEAD
                        {{ strtoupper(substr($usuario->USU_PRIMER_NOMBRE, 0, 1) . substr($usuario->USU_PRIMER_APELLIDO ?? 'U', 0, 1)) }}
=======
                        {{ strtoupper(substr(Auth::user()->name, 0, 1) . substr(Auth::user()->lastname ?? 'U', 0, 1)) }}
>>>>>>> origin/backend-Elias
                    @else
                        US
                    @endauth
                </div>
            </div>
            
<<<<<<< HEAD
            <h3 class="docente-nombre">{{ $usuario->USU_PRIMER_NOMBRE }} {{ $usuario->USU_PRIMER_APELLIDO }}</h3>
            <p class="docente-email">{{ $usuario->USU_CORREO }}</p>
=======
            <h3 class="docente-nombre">{{ Auth::user()->name ?? 'Usuario' }} {{ Auth::user()->lastname ?? '' }}</h3>
            <p class="docente-email">{{ Auth::user()->email ?? 'correo@ejemplo.com' }}</p>
>>>>>>> origin/backend-Elias

            <div class="informacion-lateral-lista">
                <div class="item-lateral">
                    <span class="item-titulo">Estado</span>
<<<<<<< HEAD
                    <span class="item-valor">{{ $usuario->USU_ESTADO ?? 'Activo' }}</span>
                </div>
                <div class="item-lateral">
                    <span class="item-titulo">Rol</span>
                    <span class="item-valor" style="text-transform: capitalize;">{{ $usuario->role->name ?? 'Sin Rol' }}</span>
=======
                    <span class="item-valor">Activo</span>
                </div>
                <div class="item-lateral">
                    <span class="item-titulo">Rol</span>
                    <span class="item-valor" style="text-transform: capitalize;">{{ Auth::user()->rol ?? 'Docente' }}</span>
>>>>>>> origin/backend-Elias
                </div>
                <div class="item-lateral">
                    <span class="item-titulo">Reservas activas</span>
                    <span class="item-valor badge-reserva">5</span>
                </div>
            </div>

<<<<<<< HEAD
            <div class="acciones-laterales">
                {{-- Botón Editar Perfil --}}
                <x-botones.boton id="btn-editar-perfil" type="button" class="btn-siger-accion btn-verde-siger">
                    Editar Perfil
                </x-botones.boton>

                {{-- Historial de reservas --}}
                <x-botones.boton type="button" class="btn-siger-accion btn-amarillo" style="color: white;">
                    Historial de reserva
                </x-botones.boton>

                {{-- SECCIÓN EXCLUSIVA PARA EL RECTOR/RECTORA --}}
                @auth
                    @if(in_array(strtolower($usuario->role->name ?? ''), ['rector', 'rectora']))
                        <a href="{{ Route::has('informes.inventario') ? route('informes.inventario') : '#' }}" style="text-decoration: none; width: 100%;">
                            <x-botones.boton type="button" class="btn-siger-accion btn-azul">
                                Ver Informes de Inventario
                            </x-botones.boton>
                        </a>
                    @endif
                @endauth

                {{-- Botón Logout compacto --}}
                <x-botones.boton-logout />
            </div>
=======
             <div class="acciones-laterales">
                    {{-- Botón Editar Perfil --}}
                    <x-botones.boton id="btn-editar-perfil" type="button" class="btn-siger-accion btn-verde-siger">
                        Editar Perfil
                    </x-botones.boton>

                    {{-- Historial de reservas --}}
                    <x-botones.boton type="button" class="btn-siger-accion btn-amarillo" style="color: white;">
                        Historial de reserva
                    </x-botones.boton>

            {{-- SECCIÓN EXCLUSIVA PARA EL RECTOR --}}
            @auth
                @if(Auth::user()->rol === 'rector')
                    <a href="{{ route('informes.inventario') }}" style="text-decoration: none; width: 100%;">
                        <x-botones.boton type="button" class="btn-siger-accion btn-azul">
                            Ver Informes de Inventario
                        </x-botones.boton>
                    </a>
                @endif
            @endauth

            {{-- Botón Logout compacto en el mismo grupo --}}
            <x-botones.boton-logout />
        </div>
>>>>>>> origin/backend-Elias
        </aside>

        {{-- COLUMNA DERECHA --}}
        <main class="perfil-columna-derecha">
            
            {{-- SECCIÓN 1: FORMULARIO DE EDICIÓN --}}
            <div class="formulario-desplegable" id="contenedor-formulario">
                <div class="tarjeta-blanca-datos">
                    <div class="titulo-ficha-datos">
                        <span>Información personal</span>
                    </div>

<<<<<<< HEAD
                    {{-- Formulario envuelto con su ruta de actualización --}}
                    <form action="{{ route('perfil.actualizar') }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('components.formularios.form-usuario', [   
                            'usuario' => $usuario,
                            'modo' => 'perfil'
                        ])
                    </form>
=======
                    @include('components.formularios.form-usuario', ['usuario' => $usuario])
>>>>>>> origin/backend-Elias
                </div>
            </div>

            {{-- SECCIÓN 2: MIS RESERVAS --}}
            <div class="tarjeta-blanca-datos" id="contenedor-reservas">
                <div class="titulo-ficha-datos">
                    <span>Mis Reservas</span>
                </div>
                <div class="modulo-placeholder">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="placeholder-icon"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <p>Próximamente: Listado y control de reservas asignadas al usuario.</p>
                </div>
            </div>

        </main>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnEditar = document.getElementById('btn-editar-perfil');
<<<<<<< HEAD
=======
        const btnCancelar = document.getElementById('btn-perfil-cancelar');
>>>>>>> origin/backend-Elias
        const contenedorForm = document.getElementById('contenedor-formulario');

        if(btnEditar && contenedorForm) {
            btnEditar.addEventListener('click', function() {
                contenedorForm.classList.toggle('activo');
            });
        }
<<<<<<< HEAD
=======

        if(btnCancelar && contenedorForm) {
            btnCancelar.addEventListener('click', function(e) {
                e.preventDefault();
                contenedorForm.classList.remove('activo');
            });
        }
>>>>>>> origin/backend-Elias
    });
</script>

@endsection