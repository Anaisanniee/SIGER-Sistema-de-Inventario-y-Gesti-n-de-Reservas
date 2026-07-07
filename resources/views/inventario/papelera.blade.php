@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/pages/papelera.css') }}">

<div class="panel-administracion-contenedor" style="padding: 20px;">
    
    {{-- Botón para regresar al Inventario Principal --}}
    <div style="margin-bottom: 20px;">
        <x-botones.boton clase="btn-azulado" url="{{ url('/inventario') }}">
            <i class="fas fa-arrow-left"></i> Volver a Gestión
        </x-botones.boton>
    </div>

    {{--- 1. CABECERA DEL PANEL ---}}
    <div class="cabecera-panel" style="margin-bottom: 25px;">
        <div class="texto-cabecera">
            <h2 class="titulo-pagina" style="font-family: var(--fuente-secundaria); font-weight: 700; color: var(--color-texto); margin-bottom: 5px;">
                <i class="fas fa-trash-alt"></i> Papelera de Recuperación
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
                            {{-- Ejemplo de fila dinámica (Tu compañero usará @foreach($activosBorrados as $activo)) --}}
                            <tr>
                                <td>
                                    <span style="font-weight: 700; color: var(--color-texto); display: block;">Proyector Epson K301</span>
                                    <small style="color: var(--color-texto-secundario); font-size: 0.8rem;">Tecnología / Multimedia</small>
                                </td>
                                <td style="font-family: var(--fuente-principal); color: var(--color-texto);">EPS-X49-98765</td>
                                <td><span class="badge-motivo-baja">Lente Quemado / Falla de placa madre</span></td>
                                <td style="text-align: center;">
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        {{-- Botón Ojo: Muestra el modal con los detalles pesados que mande el Backend --}}
                                        <x-botones.boton type="button" clase="btn-azulado" style="padding: 6px 10px; font-size: 0.85rem;"
                                            onclick="verDetallesPapelera('Proyector Epson K301', '<strong>Marca:</strong> Epson <br> <strong>Resevable:</strong> Sí <br> <strong>Fecha de Registro:</strong> 12/03/2024 <br> <strong>Último Estado:</strong> Malo <br> <strong>Fecha de Baja:</strong> 15/08/2026')">
                                            <i class="fas fa-eye"></i>
                                        </x-botones.boton>

                                        <x-botones.boton type="button" clase="btn-verde" style="padding: 6px 12px; font-size: 0.85rem;">
                                            <i class="fas fa-undo"></i> Restaurar
                                        </x-botones.boton>

                                        <x-botones.boton type="button" clase="btn-rojo" style="padding: 6px 12px; font-size: 0.85rem;">
                                            <i class="fas fa-times-circle"></i> Destruir
                                        </x-botones.boton>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 🚪 PANE 2: TABLA DE AULAS ELIMINADAS (Oculta por defecto) --}}
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
                            {{-- Ejemplo de fila dinámica --}}
                            <tr>
                                <td>
                                    <span style="font-weight: 700; color: var(--color-texto); display: block;">Laboratorio de Informática B</span>
                                    <small style="color: var(--color-texto-secundario); font-size: 0.8rem;">laboratorio</small>
                                </td>
                                <td style="font-family: var(--fuente-principal); color: var(--color-texto);">25 Personas</td>
                                <td><span class="badge-motivo-baja">Remodelación Estructural e Inundación</span></td>
                                <td style="text-align: center;">
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        {{-- Botón Ojo para el Aula --}}
                                        <x-botones.boton type="button" clase="btn-azulado" style="padding: 6px 10px; font-size: 0.85rem;"
                                            onclick="verDetallesPapelera('Laboratorio de Informática B', '<strong>Tipo de Aula:</strong> Laboratorio <br> <strong>Reservable:</strong> Sí <br> <strong>Último Estado:</strong> Disponible <br> <strong>Fecha de Baja:</strong> 05/07/2026')">
                                            <i class="fas fa-eye"></i>
                                        </x-botones.boton>

                                        <x-botones.boton type="button" clase="btn-verde" style="padding: 6px 12px; font-size: 0.85rem;">
                                            <i class="fas fa-undo"></i> Restaurar
                                        </x-botones.boton>

                                        <x-botones.boton type="button" clase="btn-rojo" style="padding: 6px 12px; font-size: 0.85rem;">
                                            <i class="fas fa-times-circle"></i> Destruir
                                        </x-botones.boton>
                                    </div>
                                </td>
                            </tr>
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