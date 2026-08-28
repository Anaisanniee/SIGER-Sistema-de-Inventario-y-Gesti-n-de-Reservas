@extends('layouts.app')
@section('rutaRegresar', route('inventario.index'))
@section('content')

<link rel="stylesheet" href="{{ asset('css/pages/papelera.css') }}">

<div class="panel-administracion-contenedor" style="padding: 20px;">
    


    {{--- 1. CABECERA DEL PANEL ---}}
    <div class="cabecera-panel" style="margin-bottom: 25px;">
        <div class="texto-cabecera">
            <h2 class="titulo-pagina" style="font-family: var(--fuente-secundaria); font-weight: 700; color: var(--color-principal); margin-bottom: 5px;">
                <i class="fas fa-trash-alt" style="margin-right: 5px;"></i> Papelera de Recuperación
            </h2>
            <p class="subtitulo-pagina" style="font-family: var(--fuente-principal); color: var(--color-texto-secundario); font-size: 0.95rem;">
                Consulta, restaura o elimina definitivamente los recursos dados de baja de la institución.
            </p>
        </div>
    </div>

    {{--- 2. CONTENEDOR PRINCIPAL (Misma estructura visual del Perfil/Dashboard) ---}}
    <div class="card-siger-papelera">
        
        {{-- PESTAÑAS (TABS) SUPERIORES --}}
        <div class="tabs-papelera-contenedor">
            <button class="btn-tab-siger active-tab" onclick="cambiarTabPapelera('tab-activos', this)">
                <i class="fas fa-boxes"></i> Activos Eliminados
            </button>
            <button class="btn-tab-siger" onclick="cambiarTabPapelera('tab-aulas', this)">
                <i class="fas fa-school"></i> Aulas Eliminadas
            </button>
        </div>

        {{--- 3. CONTENIDO DE LAS TABLAS ---}}
        <div class="tab-content-papelera">

            {{-- 🛠️ PANE 1: TABLA DE ACTIVOS ELIMINADOS --}}
            <div id="tab-activos" class="seccion-papelera-pane">
                <div class="tabla-papelera-wrapper">
                    <table class="table table-hover align-middle tabla-siger">
                        <thead>
                            <tr>
                                <th>Recurso / Categoría</th>
                                <th>Serial</th>
                                <th>Motivo de Baja</th>
                                <th style="text-align: center; width: 250px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activos as $activo)
                            <tr>
                                <td>
                                    <span style="font-weight: 700; color: var(--color-texto); display: block;">{{ $activo->act_nombre }}</span>
                                    <small style="color: var(--color-texto-secundario); font-size: 0.8rem;">{{ $activo->categoria->cate_nombre ?? 'Sin categoría' }}</small>
                                </td>
                                <td>{{ $activo->act_serial }}</td>
                                <td><span class="badge-motivo-baja">{{ $activo->act_motivo_baja ?? 'Sin motivo' }}</span></td>
                                <td style="text-align: center;">
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        {{-- Detalles --}}
                                        <x-botones.boton type="button" clase="btn-azulado" style="display: flex; align-items: center; padding: 6px 12px; font-size: 0.85rem;"
                                            onclick="verDetallesPapelera(
                                                '{{ $activo->act_nombre }}',
                                                '<strong>Marca:</strong> {{ $activo->act_marca ?? 'N/A' }} <br> <strong>Serial:</strong> {{ $activo->act_serial }} <br> <strong>Categoria:</strong> {{ $activo->categoria->cate_nombre ?? 'Sin categoría' }} <br> <strong>Fecha de Baja:</strong> {{ $activo->deleted_at ? $activo->deleted_at->format('d/m/Y') : 'N/A' }}')">
                                            <i class="fas fa-eye"></i>
                                        </x-botones.boton>

                                        {{-- Restaurar --}}
                                        <form action="{{ route('activos.restore', $activo->act_id) }}" method="POST" style="display: inline-flex; margin: 0;">
                                            @csrf
                                            <button type="submit" class="btn btn-verde" style="display: flex; align-items: center; padding: 6px 12px; font-size: 0.85rem;">
                                                <i class="fas fa-undo"></i>&nbsp;Restaurar
                                            </button>
                                        </form>

                                        {{-- Destruir --}}
                                        <form action="{{ route('activos.forceDelete', $activo->act_id) }}" method="POST" style="display: inline-flex; margin: 0;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-rojo" style="display: flex; align-items: center; padding: 6px 12px; font-size: 0.85rem;">
                                                <i class="fas fa-times-circle"></i>&nbsp;Destruir
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 🚪 PANE 2: TABLA DE AULAS ELIMINADAS --}}
            <div id="tab-aulas" class="seccion-papelera-pane" style="display: none;">
                <div class="tabla-papelera-wrapper">
                    <table class="table table-hover align-middle tabla-siger">
                        <thead>
                            <tr>
                                <th>Nombre de Aula</th>
                                <th>Capacidad</th>
                                <th>Motivo de Baja</th>
                                <th style="text-align: center; width: 250px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($aulas as $aula)
                            <tr>
                                <td>
                                    <span style="font-weight: 700; color: var(--color-texto); display: block;">
                                        {{ $aula->aula_nombre ?? 'Sin nombre' }}
                                    </span>
                                </td>
                                <td>{{ $aula->aula_capacidad ?? '0' }} personas</td>
                                <td>
                                    <span class="badge-motivo-baja">
                                        {{ $aula->aula_motivo_baja ?? 'Sin motivo' }}
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        {{-- Detalles --}}
                                        <x-botones.boton type="button" clase="btn-azulado" style="display: flex; align-items: center; padding: 6px 12px; font-size: 0.85rem;"
                                            onclick="verDetallesPapelera(
                                                '{{ $aula->aula_nombre }}',
                                                '<strong>Capacidad:</strong> {{ $aula->aula_capacidad ?? 'N/A' }} personas <br> <strong>Estado:</strong> {{ $aula->aula_estado }} <br> <strong>Tipo de aula:</strong> {{ $aula->tipoAula->tip_aula_nombre ?? 'Sin tipo de aula' }} <br> <strong>Fecha de Baja:</strong> {{ $aula->deleted_at ? $aula->deleted_at->format('d/m/Y') : 'N/A' }}')">
                                            <i class="fas fa-eye"></i>
                                        </x-botones.boton>

                                        {{-- Restaurar --}}
                                        <form action="{{ route('aulas.restore', $aula->aula_id) }}" method="POST" style="display: inline-flex; margin: 0;">
                                            @csrf
                                            <button type="submit" class="btn btn-verde" style="display: flex; align-items: center; padding: 6px 12px; font-size: 0.85rem;">
                                                <i class="fas fa-undo"></i>&nbsp;Restaurar
                                            </button>
                                        </form>

                                        {{-- Destruir --}}
                                        <form action="{{ route('aulas.forceDelete', $aula->aula_id) }}" method="POST" style="display: inline-flex; margin: 0;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-rojo" style="display: flex; align-items: center; padding: 6px 12px; font-size: 0.85rem;">
                                                <i class="fas fa-times-circle"></i>&nbsp;Destruir
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{--- 4. MODAL COMPONENTE REUTILIZADO PARA DETALLES ---}}
<x-modal titulo="Detalles del Recurso" subtitulo="Especificaciones técnicas y de auditoría interna.">
    <div id="contenedor-detalles-baja" style="font-family: var(--fuente-principal); color: var(--color-texto); line-height: 1.8; padding: 10px 5px;">
        </div>
</x-modal>

{{--- 🚀 LOGICA JAVASCRIPT REACTIVA ---}}
<script>
// Manejo del cambio de pestañas (Tabs)
function cambiarTabPapelera(tabId, botonClickado) {
    // Ocultar todas las tablas
    document.querySelectorAll('.seccion-papelera-pane').forEach(panel => {
        panel.style.display = 'none';
    });
    // Mostrar la tabla correspondiente
    document.getElementById(tabId).style.display = 'block';
    
    // Quitar clase activa a todos los botones
    document.querySelectorAll('.btn-tab-siger').forEach(btn => {
        btn.classList.remove('active-tab');
    });
    // Añadir clase activa al presionado
    botonClickado.classList.add('active-tab');
}

// Llenar y desplegar el modal general con los campos extras
function verDetallesPapelera(nombreRecurso, htmlDatosExtra) {
    // 1. Modificar textos de la cabecera del componente modal
    document.getElementById('modal-titulo-dinamico').textContent = nombreRecurso;
    document.getElementById('modal-sub-dinamico').textContent = "Historial y especificaciones completas";
    
    // 2. Inyectar el texto detallado
    document.getElementById('contenedor-detalles-baja').innerHTML = htmlDatosExtra;
    
    // 3. Invocar al modal general nativo de Bootstrap heredado por el componente
    const modalDetalles = new bootstrap.Modal(document.getElementById('modalgeneral'));
    modalDetalles.show();
}
</script>
@endsection