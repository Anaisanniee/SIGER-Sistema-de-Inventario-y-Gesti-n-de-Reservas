@extends('layouts.app')

@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'true')
@section('rutaRegresar', url('/inventario'))

@section('content')
    <link rel="stylesheet" href="{{ asset('css/components/form-usuario.css') }}">

    <h2 class="titulo-pagina"><i class="fas fa-door-open"></i> Gestión de Activos</h2>

<div class="contenedor-registro-flexible">

    <button class="btn-toggle-formulario" type="button" data-bs-toggle="collapse" data-bs-target="#formularioColapsable">
        <span><i class="fas fa-plus"></i> Formulario de Registro</span>
        <i class="fas fa-chevron-down"></i>
    </button>

    <div class="collapse dont-collapse-md" id="formularioColapsable">
        <div class="tarjeta-blanca-datos" style="background: var(--color-fondo); padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h3 style="margin-top: 0; margin-bottom: 1.5rem; color: var(--color-principal);">Registrar Nuevo Activo</h3>

            {{-- Cargamos el componente sin pasarle variable (Limpio) --}}
            @include('components.formularios.form-activo', ['modo' => 'crear'])
        </div>
    </div>

    <div class="tarjeta-lateral-gestion">
        <div class="bloque-estadisticas">
            <h3><i class="fas fa-chart-pie"></i> Equipos y bienes</h3>
            <p class="subtexto-tarjeta">Control de activos de la institución.</p>
        </div>
        <hr class="divisor-tarjeta">
        <div class="bloque-guia-segura">
            <h3><i class="fas fa-shield-alt"></i> Normas de registro</h3>
            <div class="alerta-informativa-azul">
                <p>Asegúrese de que el recurso esté correctamente registrado antes de realizar cualquier movimiento.</p>
            </div>
        </div>
    </div>
</div>
@endsection