@extends('layouts.app')

@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'true')
@section('rutaRegresar', url('/login'))

@section('content')
{{-- Cargamos tus hojas de estilo --}}
<link rel="stylesheet" href="{{ asset('css/pages/perfil.css') }}">

<div class="siger-modulo-perfil">
    
    {{-- TARJETA BLANCA CONTENEDORA --}}
    <div class="tarjeta-blanca-datos formulario-seguridad">
        
        {{-- ENCABEZADO DE LA FICHA --}}
        <div class="perfil-header-seccion-password">
            <h2 class="perfil-titulo-principal-password">Restablecer Contraseña</h2>
            <p class="perfil-subtitulo-password">Ingresa tu nueva contraseña para recuperar el acceso al sistema SIGER.</p>
        </div>

        {{-- FORMULARIO DE RECUPERACIÓN --}}
        <form action="{{ route('password.update') }}" method="POST" id="form-cambiar-password">
            @csrf
            
            {{-- CAMPOS OCULTOS NECESARIOS PARA EL TOKEN Y EL CORREO --}}
            <input type="hidden" name="token" value="{{ $token ?? '' }}">
            <input type="hidden" name="correo" value="{{ $email ?? old('correo') }}">

            {{-- CONTENEDOR DE LA REJILLA --}}
            <div class="siger-grid-formulario">
                
                {{-- CAMPO 1: NUEVA CONTRASEÑA --}}
                <div class="grupo-formulario">
                    <label for="new_password" class="label-siger">Nueva Contraseña *</label>
                    <input type="password" id="new_password" name="password" required
                           placeholder="Mínimo 6 caracteres" class="input-siger">
                </div>

                {{-- CAMPO 2: CONFIRMAR NUEVA CONTRASEÑA --}}
                <div class="grupo-formulario">
                    <label for="new_password_confirmation" class="label-siger">Confirmar Nueva Contraseña *</label>
                    <input type="password" id="new_password_confirmation" name="password_confirmation" required
                           placeholder="Repite tu nueva contraseña" class="input-siger">
                </div>

            </div>

            {{-- MOSTRAR ERRORES SI LOS HAY --}}
            @if ($errors->any())
                <div class="alert alert-danger mt-3" style="color: #dc3545; font-size: 0.9rem;">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- BOTONES DE ACCIÓN --}}
            <div class="siger-form-acciones" style="margin-top: 20px;">
                <a href="{{ url('/login') }}" class="enlace-cancelar">
                    <x-botones.boton type="button" class="btn-siger-accion btn-cancelar-siger">
                        Cancelar
                    </x-botones.boton>
                </a>

                <x-botones.boton type="submit" class="btn-siger-accion btn-verde-siger">
                    Actualizar Contraseña
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