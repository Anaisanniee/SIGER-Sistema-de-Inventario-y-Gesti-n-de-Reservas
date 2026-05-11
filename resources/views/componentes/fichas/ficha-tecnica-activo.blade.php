<button id="openModal">Ver Ficha</button>

    <dialog id="techModal">
         <label >Nombre</label>
            
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