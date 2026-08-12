//
// Listener Global para descarte de alertas
document.addEventListener('click', function (e) {
    // Si el elemento cliqueado es el botón de cerrar (o la X dentro de él)
    const btnCerrar = e.target.closest('[data-btn-cerrar]');
    
    if (btnCerrar) {
        const tarjetaAlerta = btnCerrar.closest('[data-alerta-item]');
        
        if (tarjetaAlerta) {
            // Animación de salida suave
            tarjetaAlerta.style.transition = 'all 0.3s ease';
            tarjetaAlerta.style.opacity = '0';
            tarjetaAlerta.style.transform = 'translateX(15px)';

            setTimeout(() => {
                const contenedorPadre = tarjetaAlerta.parentElement;
                tarjetaAlerta.remove();

                // Si la alerta estaba en el Dashboard y ya no quedan más, muestra mensaje vacío
                if (contenedorPadre && contenedorPadre.id === 'contenedor-alertas-siger') {
                    if (contenedorPadre.querySelectorAll('[data-alerta-item]').length === 0) {
                        contenedorPadre.innerHTML = `
                            <div class="alerta-vacia-dashboard">
                                <i class="fas fa-check-circle icono-vacio"></i>
                                <p style="margin:0; font-weight:600;">¡Todo al día!</p>
                                <p style="margin:0; font-size:0.8rem; color: var(--color-texto-secundario);">No hay más alertas por revisar.</p>
                            </div>
                        `;
                    }
                }
            }, 300);
        }
    }
});