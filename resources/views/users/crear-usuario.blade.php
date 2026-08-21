@extends('layouts.app')

@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'true')
@section('rutaRegresar', url('/usuarios'))

@section('content')
    <link rel="stylesheet" href="{{ asset('css/components/botones.css') }}">  
    <link rel="stylesheet" href="{{ asset('css/components/form-usuario.css') }}">

    <h2 class="titulo-pagina"><i class="fas fa-user-plus"></i> Crear Usuario</h2>

<div class="contenedor-registro-flexible">

    {{-- BOTÓN DISPARADOR (Móviles) --}}
    <button class="btn-toggle-formulario" type="button" data-bs-toggle="collapse" data-bs-target="#formularioColapsable" aria-expanded="false" aria-controls="formularioColapsable">
        <span><i class="fas fa-user-plus"></i> Formulario de Registro</span>
        <i class="fas fa-chevron-down"></i>
    </button>

    {{-- CONTENEDOR COLAPSABLE --}}
    <div class="collapse dont-collapse-md" id="formularioColapsable">
        <form class="form-registrar" action="{{ route('usuarios.store') }}" method="PUT">
            @csrf

            <h3>Registrar nuevo usuario</h3>

            {{-- INYECCIÓN DEL COMPONENTE PARCIAL --}}
            @include('components.formularios.form-usuario', ['modo' => 'crear'])
  
        </form>
    </div>

    {{-- TARJETA LATERAL ESTADÍSTICAS Y GUÍA --}}
    <div class="tarjeta-lateral-gestion">
        
        <div class="bloque-estadisticas">
            <h3><i class="fas fa-chart-pie"></i> Estado del Sistema</h3>
            <p class="subtexto-tarjeta">Registro exclusivo para el rol de <strong>Secretario(a)</strong>.</p>
            
            <div class="grid-contadores">
                <div class="tarjeta-contador">
                    <span class="numero-contador">48</span>
                    <span class="etiqueta-contador">Registrados</span>
                </div>
                <div class="tarjeta-contador">
                    <span class="numero-contador">12</span>
                    <span class="etiqueta-contador">Activos</span>
                </div>
            </div>
        </div>

        <hr class="divisor-tarjeta">

        <div class="bloque-guia-segura">
            <h3><i class="fas fa-shield-alt"></i> Guía de Registro Seguro</h3>
            
            <div class="alerta-informativa-azul">
                <p>Por favor, siga estas reglas para mantener la integridad de la base de datos:</p>
            </div>

            <ul class="lista-reglas-digitacion">
                <li>
                    <strong>Nombres completos:</strong> 
                    Escriba los nombres y apellidos tal como aparecen en el documento, usando mayúscula inicial (ej: <em>Juan Carlos</em>).
                </li>
                <li>
                    <strong>Documento de identidad:</strong> 
                    Digite únicamente los números. <u>No incluya</u> puntos, comas, guiones ni espacios.
                </li>
                <li>
                    <strong>Correo:</strong> 
                    Valide que el correo termine en un dominio oficial (ej: <em>@colegio.edu.co o @gmail.com</em>).
                </li>
                <li>
                    <strong>Seguridad inicial:</strong> 
                    La contraseñase inicial se recomienda que sea igual al número de documento del usuario, para facilitar el primer acceso.
                </li>
            </ul>
        </div>

    </div>

</div>
@endsection