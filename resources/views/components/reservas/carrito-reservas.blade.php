<!-- COMPONENTE: BARRA / PANEL FLOTANTE DEL CARRITO DE RESERVAS -->
<div id="reserva-carrito-flotante" class="carrito-reserva-contenedor d-none">
    <div class="carrito-reserva-card">
        
        <!-- CABECERA DEL CARRITO -->
        <div class="carrito-header" onclick="toggleDetalleCarrito()">
            <div class="carrito-info">
                <i class="fas fa-shopping-basket icono-carrito"></i>
                <span class="titulo-carrito">Recursos seleccionados:</span>
                <span id="contador-carrito-badge" class="badge-contador">0</span>
            </div>
            
            <div class="carrito-acciones-rapidas">
                <button type="button" class="btn-toggle-carrito" aria-label="Desplegar resumen">
                    <i id="flecha-toggle-carrito" class="fas fa-chevron-up"></i>
                </button>
            </div>
        </div>

        <!-- LISTA DESPLEGABLE DE ÍTEMS EN EL CARRITO -->
        <div id="carrito-desplegable-contenido" class="carrito-cuerpo d-none">
            <div id="lista-items-carrito" class="lista-items-scroll">
                <!-- Se llena dinámicamente con JS -->
            </div>

            <div class="carrito-footer">
                <x-botones.boton type="button" class="btn btn-rojo btn-vaciar-carrito" onclick="vaciarCarrito()">
                    <i class="fas fa-trash-alt"></i> Vaciar
                </x-botones.boton>

                <x-botones.boton type="button" class="btn btn-verde" data-bs-toggle="modal" data-bs-target="#modalConfirmarReservaMultiple">
                    <i class="fas fa-paper-plane"></i> Solicitar Reserva
                </x-botones.boton>
            </div>
        </div>

    </div>
</div>