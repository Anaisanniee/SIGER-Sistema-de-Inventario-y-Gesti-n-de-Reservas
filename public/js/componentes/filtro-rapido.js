document.addEventListener('DOMContentLoaded', function () {
    const contenedorFiltro = document.getElementById('filtro-rapido-componente');
    
    // 2. Crear los botones dinámicamente con JS puro
    if (contenedorFiltro && misCategorias.length > 0) {
        misCategorias.forEach(opcion => {
            // Convierte "Bloque A" en "bloque-a" para usarlo de filtro
            const slug = opcion.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/[^a-z0-9]/g, '-');
            
            contenedorFiltro.innerHTML += `
                <button class="filtro-item-btn" data-filtro="${slug}">
                    ${opcion}
                </button>
            `;
        });
    }

    // 3. Lógica para detectar el clic y filtrar las tarjetas
    const botonesFiltro = document.querySelectorAll('.filtro-item-btn');
    // Asegúrate de que tus tarjetas tengan esta clase para que el script las encuentre
    const tarjetasAulas = document.querySelectorAll('.card-aula-principal'); 

    botonesFiltro.forEach(boton => {
        boton.addEventListener('click', function () {
            // Cambiar el estado visual del botón activo
            botonesFiltro.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const filtroSeleccionado = this.getAttribute('data-filtro');

            tarjetasAulas.forEach(tarjeta => {
                // Leemos las etiquetas asignadas a la tarjeta
                const tagsTarjeta = tarjeta.getAttribute('data-tags') || '';

                if (filtroSeleccionado === 'todos') {
                    tarjeta.style.display = 'block'; 
                } else if (tagsTarjeta.includes(filtroSeleccionado)) {
                    tarjeta.style.display = 'block'; 
                } else {
                    tarjeta.style.display = 'none'; 
                }
            });
        });
    });
});