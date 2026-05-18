{{-- 1. SECCIÓN IDENTIFICACIÓN --}}
<div class="seccion-identificacion">
    <h3 class="modal-title">Identificación</h3>
    <div class="grid-tres-columnas">
        
        {{-- Categoría (Común) --}}
        <div class="form-group">
            <label>Categoría</label>
            <p id="ficha-categoria">Cargando...</p>
        </div>

        {{-- Nombre/Descripción (Común) --}}
        <div class="form-group">
            <label>Nombre del Recurso</label>
            <p id="ficha-nombre">Cargando...</p>
        </div>

        {{-- Reservable (Común) --}}
        <div class="form-group">
            <label>Reservable</label>
            <p id="ficha-reservable">Cargando...</p>
        </div>

    </div>
</div>

{{-- 2. SECCIÓN ESPECIFICACIONES --}}
<div class="seccion-estado mt-4">
    <h3 class="modal-title">Especificaciones</h3>
    
    {{-- BLOQUE CONDICIONAL PARA ACTIVOS --}}
    <div class="grid-tres-columnas" id="bloque-especificaciones-activo" style="display: none;">
        <div class="form-group">
            <label>Serial</label>
            <p id="ficha-serial">Cargando...</p>
        </div>

        <div class="form-group">
            <label>Marca</label>
            <p id="ficha-marca">Cargando...</p>
        </div>

        <div class="form-group">
            <label>Estado Físico</label>
            <p id="ficha-estado-activo">Cargando...</p>
        </div>

        <div class="form-group">
            <label>Fecha de Ingreso</label>
            <p id="ficha-fecha">Cargando...</p>
        </div>

        <div class="form-group">
            <label>Aula Ubicación</label>
            <p id="ficha-aula-nombre" class="text-primary fw-bold">Cargando...</p>
        </div>

        {{-- Precio Actual (Traído desde historial_precios) --}}
        <div class="form-group">
            <label>Precio Actual</label>
            <p id="ficha-precio" class="text-success fw-bold">Cargando...</p>
        </div>
    </div>

    {{-- BLOQUE CONDICIONAL PARA AULAS --}}
    <div class="grid-tres-columnas" id="bloque-especificaciones-aula" style="display: none;">
        <div class="form-group">
            <label>Capacidad</label>
            <p id="ficha-capacidad">Cargando...</p>
        </div>

        <div class="form-group">
            <label>Estado del Aula</label>
            <p id="ficha-estado-aula">Cargando...</p>
        </div>

        <div class="form-group">
            <label>Tipo Aula ID</label>
            <p id="ficha-tipo-aula">Cargando...</p>
        </div>
    </div>
</div>