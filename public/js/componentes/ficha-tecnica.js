document.addEventListener('DOMContentLoaded', () => {

    const modal = document.getElementById('modalgeneral');

    if(modal){

        modal.addEventListener('show.bs.modal', (event) => {

            const boton = event.relatedTarget;

            const nombre = boton.getAttribute('data-nombre');

            modal.querySelector('.modal-title').textContent = nombre;

        });

    }

});