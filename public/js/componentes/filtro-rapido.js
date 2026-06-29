document.addEventListener('DOMContentLoaded', function () {
    // 1. Capturamos los elementos interactivos
    const botonesFiltro = document.querySelectorAll('.filtro-item-btn');
    const selectFiltro = document.getElementById('filtro-rapido-select');
    const tarjetas = document.querySelectorAll('.tarjeta-wrapper'); 

    // Función unificada que realiza el filtrado real en la interfaz
    function aplicarFiltrado(filtroSeleccionado) {
        tarjetas.forEach(tarjeta => {
            // Lee el estado o categoría asignado a la tarjeta en el HTML
            const tagsTarjeta = tarjeta.getAttribute('data-tags') || '';

            if (filtroSeleccionado === 'todos') {
                tarjeta.style.setProperty('display', 'block', 'important');
            } else if (tagsTarjeta === filtroSeleccionado || tagsTarjeta.includes(filtroSeleccionado)) {
                tarjeta.style.setProperty('display', 'block', 'important');
            } else {
                tarjeta.style.setProperty('display', 'none', 'important');
            }
        });
    }

    // 2. Escuchador para la vista de PC (Botones Ovalados)
    botonesFiltro.forEach(boton => {
        boton.addEventListener('click', function () {
            botonesFiltro.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const filtro = this.getAttribute('data-filtro');
            
            // Sincronizar el select de móvil por si acaso
            if (selectFiltro) selectFiltro.value = filtro;

            aplicarFiltrado(filtro);
        });
    });

    // 3. Escuchador para la vista Móvil (Menú desplegable)
    if (selectFiltro) {
        selectFiltro.addEventListener('change', function () {
            const filtro = this.value;

            // Sincronizar los botones de PC
            botonesFiltro.forEach(btn => {
                if (btn.getAttribute('data-filtro') === filtro) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            aplicarFiltrado(filtro);
        });
    }
});