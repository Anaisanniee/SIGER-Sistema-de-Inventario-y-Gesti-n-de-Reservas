document.addEventListener('DOMContentLoaded', function () {
    const kpis = document.querySelectorAll('.kpi-filtro');
    const tarjetas = document.querySelectorAll('.recurso-item');
    
    // CAPTURA EL FILTRO RÁPIDO INTERNO
    // Buscamos los botones o el select dentro del contenedor deL componente de filtro rápido
    // EL componente genera elementos con la clase '.filtro-item-btn' o '#filtro-rapido-select'
    const contenedorComponente = document.getElementById('filtro-rapido-componente') || document.querySelector('.filtro-rapido-contenedor');

    // Función maestra que evalúa AMBOS filtros al mismo tiempo
    function ejecutarFiltroCruzado() {
        // A. Obtener KPI activa
        const kpiActiva = document.querySelector('.kpi-filtro.active');
        const filtroKPI = kpiActiva ? kpiActiva.getAttribute('data-filtro') : 'todos';

        // B. Obtener Filtro Rápido activo (Buscamos el botón con clase .active dentro deL componente o el valor del select)
        let filtroComponente = 'todos';
        if (contenedorComponente) {
            const btnActivo = contenedorComponente.querySelector('.filtro-item-btn.active');
            const selectComponente = document.getElementById('filtro-rapido-select');
            
            if (btnActivo) {
                filtroComponente = btnActivo.getAttribute('data-filtro') || 'todos';
            } else if (selectComponente) {
                filtroComponente = selectComponente.value || 'todos';
            }
        }

        // C. Aplicar la regla estricta a cada tarjeta
        tarjetas.forEach(tarjeta => {
            const tagsTarjeta = tarjeta.getAttribute('data-tags').split(' ');

            // Condición 1: ¿Cumple con la KPI? (todos, aula o activo)
            const cumpleKPI = (filtroKPI === 'todos' || tagsTarjeta.includes(filtroKPI));

            // Condición 2: ¿Cumple con el Estado? (todos, disponible, en-mantenimiento, reservado)
            const cumpleEstado = (filtroComponente === 'todos' || tagsTarjeta.includes(filtroComponente));

            // SI CUMPLE AMBAS, SE MUESTRA. SI NO, SE OCULTA.
            if (cumpleKPI && cumpleEstado) {
                tarjeta.style.display = 'block';
            } else {
                tarjeta.style.display = 'none';
            }
        });
    }

    // Escuchador para las KPIs grandes
    kpis.forEach(kpi => {
        kpi.addEventListener('click', function () {
            kpis.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Ejecutamos el filtro combinado
            ejecutarFiltroCruzado();
        });
    });

    // Escuchador para cuando le dan clic a tu componente de filtro rápido
    if (contenedorComponente) {
        // Si tu componente usa botones (.filtro-item-btn)
        contenedorComponente.addEventListener('click', function (e) {
            if (e.target.classList.contains('filtro-item-btn')) {
                // Le damos un mini tiempo de espera (0 milisegundos) para dejar que el JS propio 
                // de tu componente le ponga la clase '.active' al botón antes de que nosotros leamos cuál es.
                setTimeout(ejecutarFiltroCruzado, 0);
            }
        });

        // Si tu componente usa un <select>
        const selectComponente = document.getElementById('filtro-rapido-select');
        if (selectComponente) {
            selectComponente.addEventListener('change', ejecutarFiltroCruzado);
        }
    }
});