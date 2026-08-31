@props([
    'action' => '',
    'mostrarEstado' => true,
    'opcionesEstado' => [
        'aprobada' => 'Aprobadas',
        'pendiente' => 'Pendientes',
        'rechazada' => 'Rechazadas / Canceladas'
    ]
])

<div class="siger-filtro-contenedor">
    <form method="GET" action="{{ $action }}" class="siger-filtro-form">
        
        @if($mostrarEstado)
            <div class="siger-filtro-campo">
                <label for="filtro_estado">Estado</label>
                <select id="filtro_estado" name="estado" class="siger-filtro-input">
                    <option value="">Todos los estados</option>
                    @foreach($opcionesEstado as $valor => $etiqueta)
                        <option value="{{ $valor }}" {{ request('estado') == $valor ? 'selected' : '' }}>
                            {{ $etiqueta }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="siger-filtro-campo">
            <label for="fecha_inicio">Fecha Desde</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" class="siger-filtro-input" value="{{ request('fecha_inicio') }}">
        </div>

        <div class="siger-filtro-campo">
            <label for="fecha_fin">Fecha Hasta</label>
            <input type="date" id="fecha_fin" name="fecha_fin" class="siger-filtro-input" value="{{ request('fecha_fin') }}">
        </div>

        <div class="siger-filtro-acciones">
            <button type="submit" class="btn-siger-filtrar">
                <i class="fas fa-filter"></i> Filtrar
            </button>
            <a href="{{ strtok(url()->full(), '?') }}" class="btn-siger-limpiar">
                Limpiar
            </a>
        </div>
    </form>
</div>

<style>
    /* ==========================================================================
   COMPONENTE: FILTRO DE FECHAS Y ESTADO (SIGER)
   Archivo: public/css/components/filtro-fecha-estado.css
   ========================================================================== */

/* Contenedor Principal del Filtro */
.siger-filtro-contenedor {
    background-color: var(--color-fondo, #ffffff);
    padding: 1.25rem;
    border-radius: var(--borde-radio, 8px);
    border: 1px solid var(--color-borde, #e5e7eb);
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
}

/* Formulario Flexbox alineado al borde inferior */
.siger-filtro-form {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    align-items: flex-end;
}

/* Grupos de Entradas (Labels e Inputs) */
.siger-filtro-campo {
    flex: 1;
    min-width: 180px;
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}

.siger-filtro-campo label {
    font-family: var(--fuente-principal, 'Inter', sans-serif);
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--color-texto, #444444);
}

/* Campos de Texto, Fechas y Selects */
.siger-filtro-input {
    width: 100%;
    padding: 0.55rem 0.75rem;
    border: 1px solid var(--color-borde, #e5e7eb);
    border-radius: var(--borde-radio, 8px);
    font-family: var(--fuente-principal, 'Inter', sans-serif);
    font-size: 0.9rem;
    color: var(--color-texto, #444444);
    background-color: var(--color-fondo, #ffffff);
    outline: none;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.siger-filtro-input:focus {
    border-color: var(--color-principal, #10b981);
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
}

/* Botones de Acción */
.siger-filtro-acciones {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.btn-siger-filtrar {
    background-color: var(--color-principal, #10b981);
    color: var(--color-fondo, #ffffff);
    border: none;
    padding: 0.55rem 1.25rem;
    border-radius: var(--borde-radio, 8px);
    font-family: var(--fuente-principal, 'Inter', sans-serif);
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: background-color 0.2s ease;
}

.btn-siger-filtrar:hover {
    background-color: var(--principal-secundario, #34811d);
}

.btn-siger-limpiar {
    background-color: transparent;
    color: var(--color-azulado, #64748b);
    border: 1px solid var(--color-borde, #e5e7eb);
    padding: 0.55rem 1rem;
    border-radius: var(--borde-radio, 8px);
    font-family: var(--fuente-principal, 'Inter', sans-serif);
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: background-color 0.2s ease, color 0.2s ease;
}

.btn-siger-limpiar:hover {
    background-color: var(--color-fondo-secundario, #d1d5db);
    color: var(--color-texto, #444444);
}

/* ==========================================================================
   ADAPTATIVIDAD (RESPONSIVE)
   ========================================================================== */
@media (max-width: 768px) {
    .siger-filtro-form {
        flex-direction: column;
        align-items: stretch;
    }

    .siger-filtro-campo {
        width: 100%;
        min-width: 100%;
    }

    .siger-filtro-acciones {
        flex-direction: column;
        width: 100%;
        margin-top: 0.5rem;
    }

    .btn-siger-filtrar,
    .btn-siger-limpiar {
        width: 100%;
        justify-content: center;
    }
}
</style>