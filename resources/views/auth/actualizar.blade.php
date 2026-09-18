@extends('layouts.app')

@section('mostrarPerfil', 'false')
@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', auth()->user()->must_change_password ? 'false' : 'true')
@section('rutaRegresar', route('perfil'))

@section('content')
<link rel="stylesheet" href="{{ asset('css/pages/perfil.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/form-usuario.css') }}">

<div class="siger-modulo-perfil">
    <div class="tarjeta-blanca-datos formulario-seguridad">

        <x-tarjetas.tarjeta-auth
            icono="fas fa-lock"
            titulo="Actualizar Contraseña"
            subtitulo="{{ auth()->user()->must_change_password ? 'Por seguridad, debes cambiar tu contraseña predeterminada antes de continuar.' : 'Ingresa tu nueva contraseña y confírmala para actualizar tu acceso al sistema SIGER.' }}">

            <x-formularios.form-cambiar-contrasena
                modo="perfil"
                :action="route('perfil.password.update')"
                textoBoton="Guardar Cambios"
                :rutaCancelar="auth()->user()->must_change_password ? '#' : route('perfil')"
            />

        </x-tarjetas.tarjeta-auth>

    </div>
</div>

{{-- Si es por obligación de primer login, cambiamos la acción del formulario de cancelar para que ejecute el POST del logout por seguridad --}}
@if(auth()->user()->must_change_password)
<form id="logout-form-cancelar" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnCancelar = document.querySelector('.btn-cancelar-siger');
        if (btnCancelar) {
            const linkCancelar = btnCancelar.closest('a');
            if (linkCancelar) {
                linkCancelar.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.getElementById('logout-form-cancelar').submit();
                });
            }
        }
    });
</script>
@endif