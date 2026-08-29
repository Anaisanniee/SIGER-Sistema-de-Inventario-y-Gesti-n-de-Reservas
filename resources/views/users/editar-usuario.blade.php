@extends('layouts.app')

@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'true')
@section('rutaRegresar', route('usuarios.index')) 
@section('mostrarPerfil', 'false')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/components/form-usuario.css') }}">

    <h2 class="titulo-pagina"><i class="fas fa-user-edit"></i> Editar Usuario</h2>

<div class="contenedor-registro-flexible">

    {{-- BOTÓN DISPARADOR --}}
    <button class="btn-toggle-formulario" type="button" data-bs-toggle="collapse" data-bs-target="#formularioColapsable" aria-expanded="false" aria-controls="formularioColapsable">
        <span><i class="fas fa-user-edit"></i> Formulario de Edición</span>
        <i class="fas fa-chevron-down"></i>
    </button>

    {{-- CONTENEDOR COLAPSABLE --}}
    <div class="collapse dont-collapse-md" id="formularioColapsable">
        <div class="tarjeta-blanca-datos" style="background: var(--color-fondo); padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h3 style="margin-top: 0; margin-bottom: 1.5rem; color: var(--color-principal);">Editar Usuario</h3>

            {{-- FORMULARIO ENVIADO A UPDATE CON MÉTODO PUT --}}
            <form action="{{ route('usuarios.update', $usuario->USU_ID) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Inyección del componente modular --}}
                @include('components.formularios.form-usuario', [
                    'usuario' => $usuario, 
                    'modo' => 'editar-admin'
                ])
            </form>
        </div>
    </div>

    {{-- TARJETA LATERAL ESTADÍSTICAS Y GUÍA --}}
    <div class="tarjeta-lateral-gestion">
        
        {{-- BLOQUE 1: RESUMEN DE CUENTAS DINÁMICO --}}
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

        {{-- BLOQUE 2: GUÍA DE REGISTRO SEGURO --}}
        <div class="bloque-guia-segura">
            <h3><i class="fas fa-shield-alt"></i> Guía de Edición Segura</h3>
            
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
                    El número de documento es ineditable para proteger la trazabilidad de los registros.
                </li>
                <li>
                    <strong>Correo:</strong> 
                    Valide que el correo termine en un dominio oficial (ej: <em>@colegio.edu.co o @gmail.com</em>).
                </li>
            </ul>
        </div>

    </div>

</div>
@endsection