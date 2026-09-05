@extends('layouts.app')

@section('mostrarPerfil', 'false')
@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'true')
@section('rutaRegresar', route('perfil.index'))

@section('content')
<link rel="stylesheet" href="{{ asset('css/pages/perfil.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/form-usuario.css') }}">

<div class="siger-modulo-perfil">
    <div class="tarjeta-blanca-datos formulario-seguridad">
        
  <x-auth.tarjeta-auth 
    icono="fas fa-lock" 
    titulo="Actualizar Contraseña" 
    subtitulo="Ingresa tu nueva contraseña y confírmala para recuperar el acceso al sistema SIGER.">


        <x-formularios.form-cambiar-contrasena 
            modo="perfil"
            :action="route('perfil.password.update')"
            textoBoton="Guardar Cambios"
            :rutaCancelar="route('perfil.index')"
        />

    </div>
</div>
@endsection