{{-- resources/views/componentes/filtros-sidebar.blade.php --}}
<div id="sidebarFiltros" class="col-md-3 d-none border-end pe-3 transition-sidebar">
    <div class="p-3 bg-white rounded-4 shadow-sm">
        <h5 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2">
            <i class="fas fa-sliders-h text-success"></i> Filtrar Búsqueda
        </h5>

        <form action="{{ url('/reservas') }}" method="GET" id="formFiltros">
            
            @if(request('buscar'))
                <input type="hidden" name="buscar" value="{{ request('buscar') }}">
            @endif

            <div class="mb-3">
                <label for="bloque" class="form-label small fw-bold text-muted">Bloque</label>
                <select name="bloque" id="bloque" class="form-select rounded-3">
                    <option value="">Todos los bloques</option>
                    <option value="A" {{ request('bloque') == 'A' ? 'selected' : '' }}>Bloque A</option>
                    <option value="B" {{ request('bloque') == 'B' ? 'selected' : '' }}>Bloque B</option>
                    <option value="C" {{ request('bloque') == 'C' ? 'selected' : '' }}>Bloque C</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="piso" class="form-label small fw-bold text-muted">Piso</label>
                <select name="piso" id="piso" class="form-select rounded-3">
                    <option value="">Todos los pisos</option>
                    <option value="1" {{ request('piso') == '1' ? 'selected' : '' }}>Piso 1</option>
                    <option value="2" {{ request('piso') == '2' ? 'selected' : '' }}>Piso 2</option>
                    <option value="3" {{ request('piso') == '3' ? 'selected' : '' }}>Piso 3</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="capacidad" class="form-label small fw-bold text-muted">Capacidad</label>
                <select name="capacidad" id="capacidad" class="form-select rounded-3">
                    <option value="">Cualquier capacidad</option>
                    <option value="1-20" {{ request('capacidad') == '1-20' ? 'selected' : '' }}>1 a 20 personas</option>
                    <option value="21-40" {{ request('capacidad') == '21-40' ? 'selected' : '' }}>21 a 40 personas</option>
                </select>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success rounded-pill fw-bold py-2 shadow-sm" style="background-color: #10b981; border: none;">
                    Aplicar Filtros
                </button>
                
                @if(request()->hasAny(['bloque', 'piso', 'capacidad', 'buscar']))
                    <a href="{{ url('/reservas') }}" class="btn btn-light border rounded-pill text-muted small py-2">
                        Limpiar Filtros
                    </a>
                @endif
            </div>

        </form>
    </div>
</div>