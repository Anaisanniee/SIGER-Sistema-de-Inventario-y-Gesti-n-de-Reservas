<div class="modal fade" id="modalgeneral" tabindex="-1" >
    <div class="modal-dialog">
        <div class="modal-content modal-content-siger">

            <div class="modal-header">
                <h4 class="modal-title">
                    Nombre del Recurso
                </h4>
                <h6 class="modal-subtitle">
                    Característica
                </h6>
                <button type="button" class="btn-close-x" data-bs-dismiss="modal" aria-label="Close">✕</button>
            </div>

            <div class="modal-body">
                {{ $slot }}
            </div>

            <div class="modal-footer">
                <x-botones.boton type="button" class="btn btn-perfil-guardar" data-bs-dismiss="modal">
                    Cerrar
                </x-botones.boton>
            </div>

        </div>
    </div>
</div>