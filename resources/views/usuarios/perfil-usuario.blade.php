@extends('layouts.app')

@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'true')
@section('rutaRegresar', url('/login'))

@section('mostrarPerfil', 'false')
@section('content')

<link rel="stylesheet" href="{{ asset('css/pages/perfil.css') }}">

<div class="siger-modulo-perfil">
    
    {{-- BARRA SUPERIOR DE CONTEXTO --}}
    <div class="perfil-header-seccion">
        <h2 class="perfil-titulo-principal">Mi perfil</h2>
        <p class="perfil-subtitulo">Gestiona tu cuenta e información</p>
    </div>

    <div class="perfil-layout-contenedor">
        
        {{-- COLUMNA IZQUIERDA: AVATAR Y ACCIONES --}}
        <aside class="perfil-columna-izquierda">
            <div class="avatar-wrapper">
                <div class="avatar-circulo">DC</div>
            </div>
            
            <h3 class="docente-nombre">Nombre de docente</h3>
            <p class="docente-email">docente@gmail.com</p>

            <div class="informacion-lateral-lista">
                <div class="item-lateral">
                    <span class="item-titulo">Estado</span>
                    <span class="item-valor">Activo</span>
                </div>
                <div class="item-lateral">
                    <span class="item-titulo">Rol</span>
                    <span class="item-valor">Docente</span>
                </div>
                <div class="item-lateral">
                    <span class="item-titulo">Reservas activas</span>
                    <span class="item-valor badge-reserva">5</span>
                </div>
            </div>

            <div class="acciones-laterales">
                <button class="btn-siger-accion btn-verde-siger" id="btn-editar-perfil">Editar Perfil</button>
                <button class="btn-siger-accion btn-oscuro-siger">Historial de reserva</button>
                <button class="btn-siger-accion btn-oscuro-siger">Actualizar contraseña</button>
            </div>
        </aside>

        {{-- COLUMNA DERECHA --}}
        <main class="perfil-columna-derecha">
            
            {{-- SECCIÓN 1: FORMULARIO DE EDICIÓN (Aparece arriba al desplegarse) --}}
            <div class="formulario-desplegable" id="contenedor-formulario">
                <div class="tarjeta-blanca-datos">
                    
                    <div class="titulo-ficha-datos">
                        <span>Información personal</span>
                    </div>

                    <form action="#" method="POST" class="grid-campos-perfil">
                        @csrf
                        
                        <div class="post-form">
                            <label for="name">Primer Nombre *</label>
                            <input type="text" id="name" name="name" required placeholder="Ej. Juan">
                        </div>

                        <div class="post-form">
                            <label for="second-name">Segundo Nombre</label>
                            <input type="text" id="second-name" name="second-name" placeholder="Ej. Carlos">
                        </div>

                        <div class="post-form">
                            <label for="lastname">Primer Apellido *</label>
                            <input type="text" id="lastname" name="lastname" required placeholder="Ej. Pérez">
                        </div>
                      
                        <div class="post-form">
                            <label for="second-last-name">Segundo Apellido</label>
                            <input type="text" id="second-last-name" name="second-last-name" placeholder="Ej. Gómez">
                        </div>

                        <div class="post-form">
                            <label for="identificacion">Cédula *</label>
                            <input type="text" id="identificacion" name="identificacion" placeholder="Número de documento">
                        </div>

                        <div class="post-form">
                            <label for="correo">Correo Electrónico *</label>
                            <input type="email" id="correo" name="correo" placeholder="correo@ejemplo.com">
                        </div>

                        <div class="botones-accion-final full-width-campo">
                            <button class="btn-siger-accion btn-oscuro-siger" id="btn-perfil-cancelar" type="button" style="width: auto; padding: 0.75rem 2rem;">Cancelar</button>
                            <button class="btn-siger-accion btn-verde-siger" id="btn-perfil-guardar" type="submit" style="width: auto; padding: 0.75rem 2rem;">Guardar</button>
                        </div>
                    </form>

                </div>
            </div>

            {{-- SECCIÓN 2: MIS RESERVAS (Siempre visible, baja automáticamente) --}}
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
        const btnCancelar = document.getElementById('btn-perfil-cancelar');
        const contenedorForm = document.getElementById('contenedor-formulario');

        // Alterna la clase 'activo' sin quitar el bloque de reservas
        btnEditar.addEventListener('click', function() {
            contenedorForm.classList.toggle('activo');
        });

        btnCancelar.addEventListener('click', function(e) {
            e.preventDefault();
            contenedorForm.classList.remove('activo');
        });
    });
</script>

@endsection