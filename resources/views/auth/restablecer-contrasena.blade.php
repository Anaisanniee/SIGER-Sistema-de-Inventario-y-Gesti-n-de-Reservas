@extends('layouts.app')

@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'true')
@section('rutaRegresar', url('/login')) // cambiar por la rutad e enviar correo (recuperar-contrasena.blade.php)

@section('content')

    <link rel="stylesheet" href="{{ asset('css/components/form-usuario.css') }}">

   <x-auth.tarjeta-auth 
    icono="fas fa-lock" 
    titulo="Restablecer Contraseña" 
    subtitulo="Ingresa tu nueva contraseña y confírmala para recuperar el acceso al sistema SIGER.">

    <x-formularios.form-cambiar-contrasena 
        modo="recuperacion"
        :action="route('password.update')"
        :token="$token ?? ''"
        :correo="$email ?? old('correo')"
        textoBoton="Actualizar"
        :rutaCancelar="url('/login')"
    />

</x-auth.tarjeta-auth>
@endsection