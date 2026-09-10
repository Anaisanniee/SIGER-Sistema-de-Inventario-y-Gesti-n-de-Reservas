@extends('layouts.app')

@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'true')
@section('rutaRegresar', route('usuarios.index')) 
@section('mostrarPerfil', 'true')

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
            <form action="{{ route('usuarios.update', $usuario->usu_id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Inyección del componente modular --}}
                @include('components.formularios.form-usuario', [
                    'usuario' => $usuario, 
                    'modo' => 'editar-admin'
                ])

                {{-- NUEVA SECCIÓN: Restablecer contraseña a cédula (Exclusivo administración) --}}
                <div class="mb-3 mt-4 p-3 border rounded" style="background-color: var(--color-fondo-secundario, #f8f9fa);">
                    <label class="form-label fw-bold text-dark" style="color: var(--color-principal);">
                        <i class="fas fa-key" style="margin-right: 5px;"></i> Gestión de Contraseña
                    </label>
                    <div class="form-check">
                        <input type="checkbox" name="restablecer_a_cedula" id="restablecer_a_cedula" class="form-check-input" value="1">
                        <label for="restablecer_a_cedula" class="form-check-label" style="cursor: pointer;">
                            Restablecer contraseña al número de documento del usuario (<strong>{{ $usuario->USU_CEDULA }}</strong>)
                        </label>
                    </div>
                    <small class="form-text text-muted d-block mt-1">
                        Al marcar esta casilla, la contraseña actual se descartará y volverá a ser la cédula del usuario automáticamente.
                    </small>
                </div>

                {{-- Botón de guardar cambios general del formulario --}}
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary" style="background-color: var(--color-principal); border: none;">
                        Guardar Cambios
                    </button>
                </div>
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