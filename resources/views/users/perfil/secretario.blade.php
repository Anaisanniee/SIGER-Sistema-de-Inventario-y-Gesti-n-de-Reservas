@extends('layouts.app')

@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'true')
@section('rutaRegresar', url('/dashboard')) {{-- O la ruta de retorno segura --}}
@section('mostrarPerfil', 'false')
@section('content')

<link rel="stylesheet" href="{{ asset('css/pages/perfil.css') }}">

<div class="siger-modulo-perfil">
    
    {{-- BARRA SUPERIOR DE CONTEXTO --}}
    <div class="perfil-header-seccion">
        <h2 class="perfil-titulo-principal">Panel de Gestión - Secretaría</h2>
        <p class="perfil-subtitulo">Control, revisión y aprobación de reservas del sistema SIGER</p>
    </div>

    <div class="perfil-layout-contenedor">
        
        {{-- COLUMNA IZQUIERDA: AVATAR Y ACCIONES --}}
        <aside class="perfil-columna-izquierda">
            <div class="avatar-wrapper">
                <div class="avatar-circulo" style="background-color: var(--color-azulado);">
                    @auth
                        {{ strtoupper(substr(Auth::user()->name, 0, 1) . substr(Auth::user()->lastname ?? 'S', 0, 1)) }}
                    @else
                        SE
                    @endauth
                </div>
            </div>
            
            <h3 class="docente-nombre">{{ Auth::user()->name ?? 'Secretaria' }} {{ Auth::user()->lastname ?? '' }}</h3>
            <p class="docente-email">{{ Auth::user()->email ?? 'secretaria@ejemplo.com' }}</p>

            <div class="informacion-lateral-lista">
                <div class="item-lateral">
                    <span class="item-titulo">Estado</span>
                    <span class="item-valor">Activo</span>
                </div>
                <div class="item-lateral">
                    <span class="item-titulo">Rol</span>
                    <span class="item-valor" style="text-transform: capitalize;">{{ Auth::user()->rol ?? 'Secretaria' }}</span>
                </div>
                <div class="item-lateral">
                    <span class="item-titulo">Pendientes</span>
                    <span class="item-valor badge-reserva" style="background-color: #eab308;">4</span>
                </div>
            </div>

            <div class="acciones-laterales">
                {{-- Botones editar, cambiar clave y gestionar usuarios --}}
                <x-botones.boton id="btn-editar-perfil" type="button" class="btn-siger-accion btn-verde-siger">
                    Editar Perfil
                </x-botones.boton>
                <x-botones.boton id="btn-cambiar-contraseña" type="button" class="btn-siger-accion btn-verde-siger">
                    Cambiar contraseña
                </x-botones.boton>
                <a href="" style="text-decoration: none; width: 100%;">
                    <x-botones.boton type="button" class="btn-siger-accion btn-azul">
                        Gestionar Usuarios
                    </x-botones.boton>
                </a>
                <x-botones.boton-logout />
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

                    {{-- Llamamos el formulario--}}
                        @include('components.formularios.form-usuario', [
                            'usuario' => $usuario ?? auth()->user()
                        ])                </div>
            </div>

            {{-- SECCIÓN 2: CONTROL CENTRAL (Pestañas de Gestión de Reservas) --}}
            <div class="tarjeta-blanca-datos">
                
                {{-- Navegación de pestañas interna --}}
                <div class="tabs-gestion-admin" style="display: flex; gap: 1rem; border-bottom: 2px solid #e2e8f0; margin-bottom: 1.5rem;">
                    <button class="tab-btn activo" data-tab="tab-pendientes" style="padding: 1rem; border: none; background: none; font-weight: bold; cursor: pointer; border-bottom: 3px solid var(--color-principal); color: var(--color-texto);">
                        Informe de reserva
                    </button>
                    <button class="tab-btn" data-tab="tab-historial-global" style="padding: 1rem; border: none; background: none; font-weight: bold; cursor: pointer; color: #64748b;">
                        Reporte de inventario
                    </button>
                </div>

                {{-- CONTENIDO PESTAÑA 1: SOLICITUDES POR APROBAR --}}
                <div class="tab-contenido" id="tab-pendientes">
                    <div class="modulo-placeholder" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 3rem 1.5rem; text-align: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="placeholder-icon" style="color: #64748b; margin-bottom: 1rem;"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        <p style="color: #475569; font-weight: 500; font-size: 1rem; margin: 0;">Bandeja de entrada: Aquí aparecerán las solicitudes de las aulas y activos listas para ser [Aprobadas] o [Rechazadas].</p>
                    </div>
                </div>

                {{-- CONTENIDO PESTAÑA 2: HISTORIAL GLOBAL --}}
                <div class="tab-contenido" id="tab-historial-global" style="display: none;">
                    <div class="modulo-placeholder" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 3rem 1.5rem; text-align: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="placeholder-icon" style="color: #64748b; margin-bottom: 1rem;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        <p style="color: #475569; font-weight: 500; font-size: 1rem; margin: 0;">Bitácora: Registro general de todas las reservas procesadas anteriormente en la institución.</p>
                    </div>
                </div>

            </div>

        </main>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Control de apertura/cierre del formulario de edición
        const btnEditar = document.getElementById('btn-editar-perfil');
        const btnCancelar = document.getElementById('btn-perfil-cancelar');
        const contenedorForm = document.getElementById('contenedor-formulario');

        if(btnEditar && contenedorForm) {
            btnEditar.addEventListener('click', function() {
                contenedorForm.classList.toggle('activo');
            });
        }

        if(btnCancelar && contenedorForm) {
            btnCancelar.addEventListener('click', function(e) {
                e.preventDefault();
                contenedorForm.classList.remove('activo');
            });
        }

        // Sistema dinámico de pestañas internas (Tabs)
        const tabs = document.querySelectorAll('.tab-btn');
        const contenidos = document.querySelectorAll('.tab-contenido');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Desactivar todos los botones de pestañas
                tabs.forEach(t => {
                    t.classList.remove('activo');
                    t.style.borderBottom = 'none';
                    t.style.color = 'var(--color-azulado)';
                });
                
                // Ocultar todos los bloques de contenido
                contenidos.forEach(c => c.style.display = 'none');

                // Activar la pestaña cliqueada
                this.classList.add('activo');
                this.style.borderBottom = '3px solid var(--color-principal)';
                this.style.color = 'inherit';

                // Mostrar el panel de datos correspondiente
                const targetTab = this.getAttribute('data-tab');
                document.getElementById(targetTab).style.display = 'block';
            });
        });
    });
</script>

@endsection