@extends('layouts.app')

@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'true')
@section('rutaRegresar', route('dashboard.secretaria'))
@section('content')

<link rel="stylesheet" href="{{ asset('css/pages/perfil.css') }}">

<div class="siger-modulo-perfil">
    
    {{-- BARRA SUPERIOR DE CONTEXTO --}}
    <div class="perfil-header-seccion">
        <h2 class="perfil-titulo-principal" style="color: var(--color-principal);">Mi Perfil</h2>
    </div>

    <div class="perfil-layout-contenedor">
        
        {{-- COLUMNA IZQUIERDA: AVATAR Y ACCIONES --}}
        <aside class="perfil-columna-izquierda">
            <div class="avatar-wrapper">
                <div class="avatar-circulo" style="background-color: var(--color-azulado);">
                    @auth
                        {{ strtoupper(substr($usuario->USU_PRIMER_NOMBRE, 0, 1) . substr($usuario->USU_PRIMER_APELLIDO ?? 'S', 0, 1)) }}
                    @else
                        SE
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
                    <span class="item-valor" style="text-transform: capitalize;">{{ $usuario->role->name ?? 'Secretaría' }}</span>
                </div>
                <div class="item-lateral">
                    <span class="item-titulo">Identificación</span>
                    <span class="item-valor">{{ $usuario->USU_CEDULA ?? 'No especificada' }}</span>
                </div>
                <div class="item-lateral">
                    <span class="item-titulo">Pendientes</span>
                    <span class="item-valor badge-reserva" style="background-color: var(--color-estado-en-mantenimiento);">4</span>
                </div>
            </div>
        </aside>

        {{-- COLUMNA DERECHA --}}
        <main class="perfil-columna-derecha">
            
            {{-- SECCIÓN 1: FORMULARIO DE EDICIÓN DESPLEGABLE --}}
            <div class="formulario-desplegable" id="contenedor-formulario">
                <div class="tarjeta-blanca-datos">
                    <div class="titulo-ficha-datos">
                        <span>Actualizar Mis Datos</span>
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

        const tabs = document.querySelectorAll('.tab-btn');
        const contenidos = document.querySelectorAll('.tab-contenido');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => {
                    t.classList.remove('activo');
                    t.style.borderBottom = 'none';
                    t.style.color = 'var(--color-azulado)';
                });
                
                contenidos.forEach(c => c.style.display = 'none');

                this.classList.add('activo');
                this.style.borderBottom = '3px solid var(--color-principal)';
                this.style.color = 'inherit';

                const targetTab = this.getAttribute('data-tab');
                document.getElementById(targetTab).style.display = 'block';
            });
        });
    });
</script>

@endsection