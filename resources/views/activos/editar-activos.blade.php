@extends('layouts.app')

@section('mostrarBusqueda', 'false')
<<<<<<< HEAD
@section('mostrarRegresar', 'true')
@section('rutaRegresar', url('/aulas'))
=======
@section('rutaRegresar', route('inventario.index'))
@section('rutaRegresar', url('/inventario'))
>>>>>>> origin/backend-Elias

@section('content')
    {{-- hoja de estilos para la creación/edición de formularios --}}
    <link rel="stylesheet" href="{{ asset('css/components/form-usuario.css') }}">

    <h2 class="titulo-pagina"><i class="fas fa-box"></i> Gestión de Activos</h2>

<div class="contenedor-registro-flexible">

    {{-- Botón colapsable para diseño móvil --}}
    <button class="btn-toggle-formulario" type="button" data-bs-toggle="collapse" data-bs-target="#formularioColapsable">
        <span><i class="fas fa-edit"></i> Formulario de Edición</span>
        <i class="fas fa-chevron-down"></i>
    </button>

    {{-- Contenedor del formulario modular --}}
   <div class="collapse dont-collapse-md" id="formularioColapsable">
        <div class="tarjeta-blanca-datos" style="background: var(--color-fondo); padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h3 class="titulo-tarjeta-formulario">Modificar Activo</h3>

@include('components.formularios.form-activo', ['activo' => $activo ?? new \stdClass(), 'esEdicion' => true])        </div>
    </div>

    {{-- Tarjeta lateral con información de guía segura --}}
    <div class="tarjeta-lateral-gestion">
        <div class="bloque-guia-segura">
            <h3><i class="fas fa-info-circle"></i> Edición de Activos</h3>
            <div class="alerta-informativa-azul">
                <p>Si el activo sufrió daños estructurales o se encuentra inhabilitada temporalmente, recuerde cambiar su estado a "En Mantenimiento".</p>
            </div>
        </div>
    </div>
</div>
@endsection