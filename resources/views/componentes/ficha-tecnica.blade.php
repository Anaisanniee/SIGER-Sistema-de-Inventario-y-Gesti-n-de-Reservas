<button id="openModal">Ver Ficha</button>

    <dialog id="techModal">

            <h2>Ficha Técnica del Producto</h2>
            <hr>
            <table>
                <tr>
                <td><strong>Modelo:</strong></td>
                <td>Pro-2026 X</td>
                </tr>
                <tr>
                <td><strong>Material:</strong></td>
                <td>Aluminio Reforzado</td>
                </tr>
            </table>
            <br>
        <button id="closeModal">Cerrar</button>
    </dialog>

@push('scripts')
<script>
    (function() {
        const modal = document.querySelector('#techModal');
        const open = document.querySelector('#openModal');
        const close = document.querySelector('#closeModal');

        if (open) open.onclick = () => modal.showModal();
        if (close) close.onclick = () => modal.close();
    })();
</script>
@push('scripts')