<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title">
                    {{ $title }}
                </h4>

                <h6 class="modal-subtitle">
                    {{ $subtitle }}
                </h6>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                </button>

            </div>

            <div class="modal-body">
                {{ $slot }}
            </div>

            <div class="modal-footer">
                <button
                    type="button"
                    class="btn"
                    data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>

    </div>

</div>