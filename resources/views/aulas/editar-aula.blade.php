@extends('layouts.app')

@section('mostrarBusqueda', 'false')
<<<<<<< HEAD
@section('mostrarRegresar', 'true')
=======
@section('rutaRegresar', route('inventario.index'))
>>>>>>> origin/backend-Elias
@section('rutaRegresar', url('/aulas'))

@section('content')
    {{-- hoja de estilos para la creación/edición de formularios --}}
    <link rel="stylesheet" href="{{ asset('css/components/form-usuario.css') }}">

    <h2 class="titulo-pagina"><i class="fas fa-door-open"></i> Gestión de Aulas</h2>

<div class="contenedor-registro-flexible">

    {{-- Botón colapsable para diseño móvil --}}
    <button class="btn-toggle-formulario" type="button" data-bs-toggle="collapse" data-bs-target="#formularioColapsable">
        <span><i class="fas fa-edit"></i> Formulario de Edición</span>
        <i class="fas fa-chevron-down"></i>
    </button>

    {{-- Contenedor del formulario modular --}}
   <div class="collapse dont-collapse-md" id="formularioColapsable">
        <div class="tarjeta-blanca-datos" style="background: var(--color-fondo); padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h3 class="titulo-tarjeta-formulario">Modificar Aula</h3>

@include('components.formularios.form-aula', ['aula' => $aula ?? new \stdClass(), 'esEdicion' => true])        </div>
    </div>

    {{-- Tarjeta lateral con información de guía segura --}}
    <div class="tarjeta-lateral-gestion">
        <div class="bloque-guia-segura">
            <h3><i class="fas fa-info-circle"></i> Edición de Espacios</h3>
            <div class="alerta-informativa-azul">
                <p>Si el aula sufrió daños estructurales o se encuentra inhabilitada temporalmente, recuerde cambiar su estado a "En Mantenimiento".</p>
            </div>
        </div>
    </div>
</div>
@endsection