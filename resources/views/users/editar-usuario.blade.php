{{-- resources/views/usuarios/editar-usuario.blade.php --}}
@extends('layouts.app')

@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'true')
@section('rutaRegresar', url('/usuarios')) 
@section('mostrarPerfil', 'false')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/components/form-usuario.css') }}">

    <h2 class="titulo-pagina"><i class="fas fa-user-edit"></i> Editar Usuario</h2>

<div class="contenedor-registro-flexible">

    {{-- BOTÓN DISPARADOR: Solo se ve en celulares/tablets (< 768px). Abre y cierra el formulario --}}
    <button class="btn-toggle-formulario" type="button" data-bs-toggle="collapse" data-bs-target="#formularioColapsable" aria-expanded="false" aria-controls="formularioColapsable">
        <span><i class="fas fa-user-edit"></i> Formulario de Edición</span>
        <i class="fas fa-chevron-down"></i>
    </button>

    {{-- CONTENEDOR COLAPSABLE: Usa el formulario modular reutilizable --}}
    <div class="collapse dont-collapse-md" id="formularioColapsable">
        <div class="tarjeta-blanca-datos" style="background: var(--color-fondo); padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h3 style="margin-top: 0; margin-bottom: 1.5rem; color: var(--color-principal);">Editar Usuario</h3>

            {{--Inyectamos formulario modular reutilizable --}}
            @include('components.formularios.form-usuario', ['usuario' => $usuario, 'modo' => 'editar-admin'])
        </div>
    </div>

    {{-- TARJETA LATERAL: Guía e información del sistema --}}
    <div class="tarjeta-lateral-gestion">
        
        {{-- BLOQUE 1: RESUMEN DE CUENTAS --}}
        <div class="bloque-estadisticas">
            <h3><i class="fas fa-chart-pie"></i> Estado del Sistema</h3>
            <p class="subtexto-tarjeta">Registro exclusivo para el rol de <strong>Secretario(a)</strong>.</p>
            
            <div class="grid-contadores">
                <div class="tarjeta-contador">
                    <span class="numero-contador">48</span> {{-- TODO: Cambiar a dinámico --}}
                    <span class="etiqueta-contador">Registrados</span>
                </div>
                <div class="tarjeta-contador">
                    <span class="numero-contador">12</span> {{-- TODO: Cambiar a dinámico --}}
                    <span class="etiqueta-contador">Activos</span>
                </div>
            </div>
        </div>

        <hr class="divisor-tarjeta">

        {{-- BLOQUE 2: GUÍA DE REGISTRO SEGURO --}}
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