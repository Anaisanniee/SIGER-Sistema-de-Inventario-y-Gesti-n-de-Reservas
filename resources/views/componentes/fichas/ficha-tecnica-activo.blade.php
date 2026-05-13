<button class = "btn-abrir-ficha-activos">Ver Ficha</button>

    <div id="techModal-activos">
         <div class="modal-content">
            <header class="modal-header">
                <div class= foto-activo>
                    <i class="fas fa-laptop"></i>
                </div>
                <div class = "datos-principales">
                    <h4>Nombre del Activo</h4>
                    <p>Serial: 123456</p>
                    <span class="badge badge-primary">Activo</span>
                </div>
            </header>


            <div class="modal-body">

                <div class="seccion identificacion">
                    <h3 class="modal-title">Identificación</h3>
                    <div class="grid-tres-columnas">

                        <div class="form-group">
                            <label for="categoria">Categoría</label>
                            <p>Electrónico</p>
                        </div>

                        <div class="form-group">
                            <label for="marca">Marca</label>
                            <p>TechCorp</p>
                        </div>

                        <div class="form-group">
                            <label for="serial">Serial</label>
                            <p>123456</p>
                        </div>

                        <div class="form-group">
                            <label for="an">Año de adquisición</label>
                            <p>2022</p>
                        </div>
                    </div>
                </div>

                <div class="seccion ubicacion">
                    <h3 class="modal-title">Estado</h3>
                    <div class="grid-tres-columnas">

                        <div class="form-group">
                            <label for="ubicacion">Ubicación</label>
                            <p>Oficina Central</p>
                        </div>

                        <div class="form-group">
                            <label for="estado-fisico">Estado Físico</label>
                            <p>Bueno</p>
                        </div>

                        <div class="form-group">
                            <label for="reservable">Reservable</label>
                            <p>Sí</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <button class="btn-cerrar-ficha-activos">Cerrar</button>
    </div>
<script src="{{ asset('/js/componentes/ficha-tecnica.js') }}"></script>