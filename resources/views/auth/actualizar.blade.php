@extends('layouts.app')

@section('mostrarPerfil', 'false')
@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'true')
@section('rutaRegresar', route('perfil'))

@section('content')
<link rel="stylesheet" href="{{ asset('css/pages/perfil.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/form-usuario.css') }}">

<div class="siger-modulo-perfil">
    <div class="tarjeta-blanca-datos formulario-seguridad">
        
        <div class="auth-card-header text-center mb-4">
            <i class="fas fa-lock fa-2x mb-2" style="color: var(--color-principal);"></i>
            <h3 class="titulo-siger">Actualizar Contraseña</h3>
            <p class="subtitulo-siger text-muted">Ingresa tu nueva contraseña y confírmala para recuperar el acceso al sistema SIGER.</p>
        </div>

        <x-formularios.form-cambiar-contrasena 
            modo="perfil"
            :action="route('perfil.password.update')"
            textoBoton="Guardar Cambios"
            :rutaCancelar="route('perfil')"
        />

    </div>
</div>
@endsection