<button class = "btn-abrir-ficha-aula">Ver Ficha</button>

    <div id="techModal-aula">
         <div class="modal-content">
            <header class="modal-header">
                <div class= foto-aula>
                    <i class="bi bi-geo-alt"></i>
                </div>
                <div class = "datos-principales">
                    <h4>Nombre del Aula</h4>
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
                            <label for="capacidad">Capacidad</label>
                            <p>30</p>
                        </div>

                    </div>
                </div>

                <div class="seccion ubicacion">
                    <h3 class="modal-title">Estado</h3>
                    <div class="grid-tres-columnas">


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
        <button class="btn-cerrar-ficha-aula">Cerrar</button>
    </div>
<script src="{{ asset('/js/componentes/ficha-tecnica.js') }}"></script>