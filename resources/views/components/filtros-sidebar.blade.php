<div id="sidebarFiltros" class="col-md-3 px-4 border-end d-none" style="min-height: 80vh;">
    <h5 class="text-muted fw-bold mb-3" style="font-size: 14px; letter-spacing: 1px;">FILTROS</h5>
    
    <form action="{{ url('/reservas') }}" method="GET">
        <div class="mb-4">
            <label class="form-label fw-bold text-secondary mb-1">Bloque</label>
            <select name="bloque" class="form-select bg-light border-0 py-2 rounded-3 text-muted">
                <option value="">Todos los bloques</option>
                <option value="A" {{ request('bloque') == 'A' ? 'selected' : '' }}>Bloque A</option>
                <option value="B" {{ request('bloque') == 'B' ? 'selected' : '' }}>Bloque B</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold text-secondary mb-1">Piso</label>
            <select name="piso" class="form-select bg-light border-0 py-2 rounded-3 text-muted">
                <option value="">Todos los pisos</option>
                <option value="1" {{ request('piso') == '1' ? 'selected' : '' }}>Piso 1</option>
                <option value="2" {{ request('piso') == '2' ? 'selected' : '' }}>Piso 2</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold text-secondary mb-2">Capacidad</label>
            <select name="capacidad" class="form-select bg-light border-0 py-2 rounded-3 text-muted">
                <option value="">Todos</option>
                <option value="1-20" {{ request('capacidad') == '1-20' ? 'selected' : '' }}>1 - 20 personas</option>
                <option value="21-40" {{ request('capacidad') == '21-40' ? 'selected' : '' }}>21 - 40 personas</option>
            </select>
        </div>

        <div class="d-grid gap-2 mt-5">
            <button type="submit" class="btn btn-aplicar text-white fw-bold py-2 rounded-3">Aplicar filtros</button>
            <a href="{{ url('/reservas') }}" class="btn btn-limpiar text-white fw-bold py-2 rounded-3 text-center text-decoration-none">Limpiar filtros</a>
        </div>
    </form>
</div>