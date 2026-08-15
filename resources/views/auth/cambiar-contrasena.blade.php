@extends('layouts.app')

@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'true')
@section('rutaRegresar', url('/dashboard'))

@section('content')
{{-- Cargamos tus hojas de estilo --}}
<link rel="stylesheet" href="{{ asset('css/pages/perfil.css') }}">

<div class="siger-modulo-perfil">
    
    {{-- TARJETA BLANCA CONTENEDORA --}}
    <div class="tarjeta-blanca-datos formulario-seguridad">
        
        {{-- ENCABEZADO DE LA FICHA --}}
        <div class="perfil-header-seccion-password">
            <h2 class="perfil-titulo-principal-password">Seguridad de la Cuenta</h2>
            <p class="perfil-subtitulo-password">Modifica tu contraseña para mantener protegida tu información en el sistema SIGER.</p>
        </div>

        {{-- FORMULARIO --}}
        <form action="#" method="POST" id="form-cambiar-password">
            @csrf
            @method('PUT')

            {{-- CONTENEDOR DE LA REJILLA --}}
            <div class="siger-grid-formulario">
                
                {{-- CAMPO 1: CONTRASEÑA ACTUAL --}}
                <div class="grupo-formulario">
                    <label for="current_password" class="label-siger">Contraseña Actual *</label>
                    <input type="password" id="current_password" name="current_password" required
                           placeholder="Ingresa tu contraseña actual" class="input-siger">
                </div>

                {{-- ESPACIO EN BLANCO PARA REJILLA --}}
                <div class="grupo-formulario-vacio"></div>

                {{-- CAMPO 2: NUEVA CONTRASEÑA --}}
                <div class="grupo-formulario">
                    <label for="new_password" class="label-siger">Nueva Contraseña *</label>
                    <input type="password" id="new_password" name="new_password" required
                           placeholder="Mínimo 6 caracteres" class="input-siger">
                </div>

                {{-- CAMPO 3: CONFIRMAR NUEVA CONTRASEÑA --}}
                <div class="grupo-formulario">
                    <label for="new_password_confirmation" class="label-siger">Confirmar Nueva Contraseña *</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                           placeholder="Repite tu nueva contraseña" class="input-siger">
                </div>

            </div>

            {{-- BOTONES DE ACCIÓN --}}
            <div class="siger-form-acciones">
                <a href="{{ url('/dashboard') }}" class="enlace-cancelar">
                    <x-botones.boton type="button" class="btn-siger-accion btn-cancelar-siger">
                        Cancelar
                    </x-botones.boton>
                </a>

                <x-botones.boton type="submit" class="btn-siger-accion btn-verde-siger">
                    Guardar Cambios
                </x-botones.boton>
            </div>
        </form>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form-cambiar-password');
        const newPassword = document.getElementById('new_password');
        const confirmPassword = document.getElementById('new_password_confirmation');

        form.addEventListener('submit', function(e) {
            if (newPassword.value !== confirmPassword.value) {
                e.preventDefault();
                alert('¡Atención! La nueva contraseña y su confirmación no coinciden.');
                confirmPassword.focus();
            } else if (newPassword.value.length < 6) {
                e.preventDefault();
                alert('Por seguridad, la nueva contraseña debe contener un mínimo de 6 caracteres.');
                newPassword.focus();
            }
        });
    });
</script>
@endsection