{{-- resources/views/componentes/filtro-prestamos.blade.php --}}
<div class="collapse mb-4" id="collapseFiltros">
    <div class="card card-body border-0 shadow-sm rounded-4 p-4 bg-light">
        <form action="{{ route('inventario.prestamos') }}" method="GET" id="formFiltros">
            <div class="row g-3">
                
                <div class="col-md-6">
                    <label class="form-label text-secondary fw-semibold small mb-2">Tipo de equipo</label>
                    <div class="d-flex flex-wrap gap-2">
                        <input type="radio" class="btn-check" name="tipo" id="tipo_all" value="" {{ request('tipo') == '' ? 'checked' : '' }} onchange="this.form.submit()">
                        <label class="btn btn-outline-secondary rounded-3 px-3 py-2 small" for="tipo_all">✨ Todos</label>

                        <input type="radio" class="btn-check" name="tipo" id="tipo_laptop" value="fa-laptop" {{ request('tipo') == 'fa-laptop' ? 'checked' : '' }} onchange="this.form.submit()">
                        <label class="btn btn-outline-secondary rounded-3 px-3 py-2 small" for="tipo_laptop"> Portátiles</label>

                        <input type="radio" class="btn-check" name="tipo" id="tipo_tablet" value="fa-tablet-alt" {{ request('tipo') == 'fa-tablet-alt' ? 'checked' : '' }} onchange="this.form.submit()">
                        <label class="btn btn-outline-secondary rounded-3 px-3 py-2 small" for="tipo_tablet"> Tablets</label>

                        <input type="radio" class="btn-check" name="tipo" id="tipo_video" value="fa-video" {{ request('tipo') == 'fa-video' ? 'checked' : '' }} onchange="this.form.submit()">
                        <label class="btn btn-outline-secondary rounded-3 px-3 py-2 small" for="tipo_video"> Proyectores</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-secondary fw-semibold small mb-2">Disponibilidad</label>
                    <div class="d-flex gap-3 pt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="disponibilidad" id="disp_todos" value="" {{ request('disponibilidad') == '' ? 'checked' : '' }} onchange="this.form.submit()" style="accent-color: #00b18d;">
                            <label class="form-check-label small text-muted fw-medium" for="disp_todos">Todos</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="disponibilidad" id="disp_solo" value="Disponible" {{ request('disponibilidad') == 'Disponible' ? 'checked' : '' }} onchange="this.form.submit()" style="accent-color: #00b18d;">
                            <label class="form-check-label small text-muted fw-medium" for="disp_solo">Solo disponibles</label>
                        </div>
                    </div>
                </div>

            </div>

            @if(request('tipo') || request('disponibilidad') || request('buscar'))
                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('inventario.prestamos') }}" class="btn btn-sm btn-outline-danger rounded-3 fw-bold px-3">Limpiar Filtros</a>
                </div>
            @endif
        </form>
    </div>
</div>