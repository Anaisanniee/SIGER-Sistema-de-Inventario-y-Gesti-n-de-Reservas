@extends('layouts.app')

@section('content')
{{-- Botón para regresar al inventario --}}
<div style="margin-bottom: 20px;">
    <a href="{{ url('/inventario') }}" class="btn btn-secondary" style="border-radius: 4px; padding: 8px 16px; text-decoration: none; color: var(--color-texto);">
        <i class="fas fa-arrow-left"></i> Volver al Inventario
    </a>
</div>

{{--- 1. CABECERA DE LA PAPELERA ---}}
<div class="cabecera-panel" style="margin-bottom: 30px;">
    <h2><i class="fas fa-trash-alt"></i> Papelera de Recuperación</h2>
    <p class="subtitulo-pagina">Aquí puedes restaurar los recursos eliminados o borrarlos definitivamente del sistema.</p>
</div>

{{--- 2. BOTONES DE PESTAÑAS (TABS) ---}}
<div class="d-flex gap-2" style="border-bottom: 2px solid var(--color-borde,var(--color-fondo)); margin-bottom: 25px; padding-bottom: 10px;">
    <button class="btn btn-tab active" onclick="cambiarPapelera('tab-activos', this)" style="border: none; background: none; font-weight: bold; border-bottom: 3px solid var(--color-reservado-pastel, --color-estado-reservado); padding: 5px 15px;">
        <i class="fas fa-tools"></i> Activos Eliminados
    </button>
    <button class="btn btn-tab" onclick="cambiarPapelera('tab-aulas', this)" style="border: none; background: none; color: gray; padding: 5px 15px;">
        <i class="fas fa-door-open"></i> Aulas Eliminadas
    </button>
</div>

{{--- 3. CONTENEDOR DE SECCIONES ---}}
<div class="secciones-papelera">

    {{-- SECCIÓN 1: ACTIVOS ELIMINADOS --}}
    <div id="tab-activos" class="contenido-tab">
        <div class="container-tarjetas">
            {{-- Aquí haces el @foreach de los activos borrados que te mande el backend --}}
            {{-- En lugar de botones de Editar/Eliminar, les pones botones de "Restaurar" (Verde) y "Destruir" (Rojo) --}}
            <div class="tarjeta-wrapper">
                {{-- Ejemplo de tarjeta compacta de papelera o reusa tu tarjeta con acciones cambiadas --}}
            </div>
        </div>
    </div>

    {{-- SECCIÓN 2: AULAS ELIMINADAS (Oculta por defecto) --}}
    <div id="tab-aulas" class="contenido-tab" style="display: none;">
        <div class="container-tarjetas">
            {{-- Aquí haces el @foreach de las aulas borradas --}}
        </div>
    </div>

</div>

{{---SCRIPT PARA CAMBIAR ENTRE PESTAÑAS ---}}
<script>
function cambiarPapelera(tabId, boton) {
    // Ocultar todas las secciones
    document.querySelectorAll('.contenido-tab').forEach(el => el.style.display = 'none');
    // Mostrar la seleccionada
    document.getElementById(tabId).style.display = 'block';
    
    // Desactivar todos los botones de pestañas
    document.querySelectorAll('.btn-tab').forEach(btn => {
        btn.style.fontWeight = 'normal';
        btn.style.color = 'gray';
        btn.style.borderBottom = 'none';
    });
    
    // Activar el botón actual
    boton.style.fontWeight = 'bold';
    boton.style.color = 'gray';
    boton.style.borderBottom = '3px solid var(--color-estado-reservado, --color-estado-reservado)';
}
</script>
@endsection