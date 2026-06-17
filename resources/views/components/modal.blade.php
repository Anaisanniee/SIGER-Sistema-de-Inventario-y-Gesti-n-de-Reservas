<div class="modal fade" id="modalgeneral" tabindex="-1" >
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                {{-- 👇 Les agregamos las clases 'modal-title' y 'modal-subtitle' para que el JS las encuentre 👇 --}}
                <h4 class="modal-title">
                    Nombre del Recurso
                </h4>
                <h6 class="modal-subtitle text-muted">
                    Característica
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                {{ $slot }}
            </div>

            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>