<div class="ficha-tecnica-universal p-4">   
    
    {{-- 1. SECCIÓN IDENTIFICACIÓN --}}
    <div class="seccion-identificacion">
        <h3 style="font-size: 1.25rem; color: #aaa; margin-bottom: 1rem;">Identificación</h3>
        <div class="grid-tres-columnas">
            <div class="form-group">
                <label>Categoría</label>
                <p id="ficha-categoria">...</p>
            </div>
            <div class="form-group">
                <label>Nombre del Recurso</label>
                <p id="ficha-nombre">...</p>
            </div>
            <div class="form-group">
                <label>Reservable</label>
                <p id="ficha-reservable">...</p>
            </div>
        </div>
    </div>

    {{-- 2. SECCIÓN ESPECIFICACIONES --}}
    <div class="seccion-estado mt-4">
        <h3 style="font-size: 1.25rem; color: #aaa; margin-bottom: 1rem;">Especificaciones</h3>
        
        {{-- BLOQUE EXCLUSIVO PARA ACTIVOS --}}
        <div class="grid-tres-columnas" id="bloque-especificaciones-activo" style="display: none;">
            <div class="form-group">
                <label>Serial</label>
                <p id="ficha-serial">...</p>
            </div>
            <div class="form-group">
                <label>Marca</label>
                <p id="ficha-marca">...</p>
            </div>
            <div class="form-group">
                <label>Estado Físico</label>
                <p id="ficha-estado-activo">...</p>
            </div>
            <div class="form-group">
                <label>Fecha de Ingreso</label>
                <p id="ficha-fecha">...</p>
            </div>
            <div class="form-group">
                <label>Aula Ubicación</label>
                <p id="ficha-aula-nombre" class="text-primary fw-bold">...</p>
            </div>
            <div class="form-group">
                <label>Precio Actual</label>
                <p id="ficha-precio" class="text-success fw-bold">...</p>
            </div>
        </div>

        {{-- BLOQUE EXCLUSIVO PARA AULAS --}}
        <div class="grid-tres-columnas" id="bloque-especificaciones-aula" style="display: none;">
            <div class="form-group">
                <label>Capacidad</label>
                <p id="ficha-capacidad">...</p>
            </div>
            <div class="form-group">
                <label>Estado del Aula</label>
                <p id="ficha-estado-aula">...</p>
            </div>
            <div class="form-group">
                <label>Tipo Aula</label>
                <p id="ficha-tipo-aula">...</p>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
{{-- 3. SECCIÓN ADICIONAL: INVENTARIO DE ACTIVOS --}}
{{-- ========================================== --}}
<div class="seccion-activos-asignados mt-4">
    <h3 class="modal-title">Inventario del Espacio</h3>
    
    <div class="accordion mt-2" id="acordeonInventario">
        <div class="accordion-item-siger">
                <button class="accordion-trigger" type="button" data-bs-toggle="collapse" data-bs-target="#colapsoActivos" aria-expanded="false" aria-controls="colapsoActivos">
                    <span class="trigger-content">
                        <span class="icon-box"></span>
                        <span class="text">Ver activos asignados a esta aula</span>
                    </span>
                    <span class="badge-conteo" id="ficha-conteo-activos">0</span>
                </button>

                <div id="colapsoActivos" class="collapse" data-bs-parent="#acordeonInventario">
                    <div class="accordion-body-siger">
                        <ul class="lista-activos-siger" id="contenedor-activos-dinamicos">
                            <li class="activo-item text-muted text-center py-2">
                                
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>