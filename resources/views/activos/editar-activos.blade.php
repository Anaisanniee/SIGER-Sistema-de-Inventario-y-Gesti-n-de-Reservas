@extends('layouts.app')

@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'true')
@section('rutaRegresar', route('inventario.index'))

@section('content')
    <link rel="stylesheet" href="{{ asset('css/components/form-usuario.css') }}">

    <h2 class="titulo-pagina"><i class="fas fa-box"></i> Gestión de Activos</h2>

<div class="contenedor-registro-flexible">

    <button class="btn-toggle-formulario" type="button" data-bs-toggle="collapse" data-bs-target="#formularioColapsable">
        <span><i class="fas fa-edit"></i> Formulario de Edición</span>
        <i class="fas fa-chevron-down"></i>
    </button>

    <div class="collapse dont-collapse-md" id="formularioColapsable">
        <div class="tarjeta-blanca-datos" style="background: var(--color-fondo); padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h3 class="titulo-tarjeta-formulario">Modificar Activo</h3>

            @include('components.formularios.form-activo', ['activo' => $activo ?? new \stdClass(), 'esEdicion' => true])
        </div>
    </div>

    <div class="tarjeta-lateral-gestion">
        <div class="bloque-guia-segura">
            <h3><i class="fas fa-info-circle"></i> Edición de Activos</h3>
            <div class="alerta-informativa-azul">
                <p><strong>Indicaciones para la modificación:</strong></p>
                <ul style="margin-top: 5px; padding-left: 20px;">
                    <li><strong>Justificación de Precios:</strong> Si modifica el valor comercial, asegúrese de detallar claramente el Motivo del Cambio de Precio (ej. <em>reavalúo, corrección de inventario, mantenimiento mayor</em>) para mantener la trazabilidad financiera.</li>
                    <li><strong>Control de Estado Físico:</strong> Si el activo sufrió daños estructurales o se encuentra inhabilitado temporalmente, recuerde cambiar su estado a "En Mantenimiento".</li>
                    <li><strong>Reasignación de Espacios:</strong> Verifique que el cambio de aula o espacio asignado corresponda a la ubicación física actual del equipo en la institución.</li>
                    <li><strong>Gestión de Fotografías:</strong> Si la imagen del equipo sigue siendo vigente, no es necesario seleccionar un archivo nuevo; la plataforma conservará la fotografía actual de forma automática.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection