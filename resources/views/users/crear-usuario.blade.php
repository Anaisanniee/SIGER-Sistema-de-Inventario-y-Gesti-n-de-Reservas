@extends('layouts.app')

@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'true')
@section('rutaRegresar', route('usuarios.index'))
@section('mostrarPerfil', 'true')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/components/botones.css') }}">   
    <link rel="stylesheet" href="{{ asset('css/components/form-usuario.css') }}">

    <h2 class="titulo-pagina"><i class="fas fa-user-plus"></i> Crear Usuario</h2>

<div class="contenedor-registro-flexible">

    {{-- CONTENEDOR PRINCIPAL CON LA MISMA CLASE DE LA VISTA EDITAR --}}
    <div id="formularioColapsable">
        <div class="tarjeta-blanca-datos" style="background: var(--color-fondo); padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h3 style="margin-top: 0; margin-bottom: 1.5rem; color: var(--color-principal);">Registrar nuevo usuario</h3>

            {{-- ALERTA DE ERRORES DE VALIDACIÓN --}}
            @if ($errors->any())
                <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #f5c6cb;">
                    <strong>¡Atención! No se pudo crear el usuario:</strong>
                    <ul style="margin-bottom: 0; padding-left: 1.2rem; margin-top: 0.5rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORMULARIO DE CREACIÓN --}}
            <form action="{{ route('usuarios.store') }}" method="POST">
                @csrf

                {{-- INYECCIÓN DEL COMPONENTE PARCIAL --}}
                @include('components.formularios.form-usuario', [
                    'modo' => 'crear',
                    'roles' => $roles ?? []
                ])
            </form>
        </div>
    </div>

    {{-- TARJETA LATERAL ESTADÍSTICAS Y GUÍA --}}
    <div class="tarjeta-lateral-gestion">
        
        <div class="bloque-estadisticas">
            <h3><i class="fas fa-chart-pie"></i> Estado del Sistema</h3>
            <p class="subtexto-tarjeta">Registro exclusivo para el rol de <strong>Secretario(a)</strong>.</p>
            
            <div class="grid-contadores">
                <div class="tarjeta-contador">
                    <span class="numero-contador">{{ $registrados ?? 0 }}</span>
                    <span class="etiqueta-contador">Registrados</span>
                </div>
                <div class="tarjeta-contador">
                    <span class="numero-contador">{{ $activos ?? 0 }}</span>
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
                    La contraseña inicial se recomienda que sea igual al número de documento del usuario para facilitar su primer acceso.
                </li>
            </ul>
        </div>

    </div>

</div>
@endsection