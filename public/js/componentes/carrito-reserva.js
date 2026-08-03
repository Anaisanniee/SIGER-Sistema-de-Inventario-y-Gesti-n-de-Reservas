/**
 * SISTEMA DE RESERVA MÚLTIPLE - CARRITO SIGER
 */

// Estado global del carrito
let carritoReservas = [];

document.addEventListener('DOMContentLoaded', () => {
    cargarCarritoDesdeStorage();
    actualizarInterfazCarrito();
});

// 1. Cargar datos guardados previamente
function cargarCarritoDesdeStorage() {
    const data = localStorage.getItem('siger_carrito_reservas');
    if (data) {
        try {
            carritoReservas = JSON.parse(data);
        } catch (e) {
            carritoReservas = [];
        }
    }
}

// 2. Guardar en localStorage
function guardarCarrito() {
    localStorage.setItem('siger_carrito_reservas', JSON.stringify(carritoReservas));
    actualizarInterfazCarrito();
}

// 3. Agregar o quitar un ítem
function toggleItemCarrito(recurso) {
    // recurso debe ser un objeto: { id, nombre, tipo, foto, ubicacion }
    const existeIndex = carritoReservas.findIndex(item => item.id === recurso.id && item.tipo === recurso.tipo);

    if (existeIndex > -1) {
        carritoReservas.splice(existeIndex, 1); // Remover si ya está
    } else {
        carritoReservas.push(recurso); // Agregar
    }

    guardarCarrito();
}

// 4. Vaciar carrito completo
function vaciarCarrito() {
    carritoReservas = [];
    guardarCarrito();
}

// 5. Eliminar un ítem específico desde el modal/carrito
function eliminarItemCarrito(id, tipo) {
    carritoReservas = carritoReservas.filter(item => !(item.id === id && item.tipo === tipo));
    guardarCarrito();
}

// 6. Actualizar UI (Badges, lista desplegable y botones en tarjetas)
function actualizarInterfazCarrito() {
    const contenedorCarrito = document.getElementById('reserva-carrito-flotante');
    const badgeContador = document.getElementById('contador-carrito-badge');
    const listaItems = document.getElementById('lista-items-carrito');

    if (!contenedorCarrito || !badgeContador) return;

    // A) Mostrar u ocultar barra flotante
    if (carritoReservas.length > 0) {
        contenedorCarrito.classList.remove('d-none');
        badgeContador.innerText = carritoReservas.length;
    } else {
        contenedorCarrito.classList.add('d-none');
        // Si se vacía, cerrar el desplegable
        const contenido = document.getElementById('carrito-desplegable-contenido');
        if (contenido) contenido.classList.add('d-none');
    }

    // B) Renderizar ítems en la lista del carrito
    if (listaItems) {
        if (carritoReservas.length === 0) {
            listaItems.innerHTML = '<p class="text-muted text-center my-2 fs-7">No hay recursos seleccionados</p>';
        } else {
            listaItems.innerHTML = carritoReservas.map(item => `
                <div class="item-carrito-card">
                    <div class="d-flex align-items-center gap-2">
                        <i class="${item.tipo === 'aula' ? 'fas fa-door-open' : 'fas fa-laptop'} text-primary"></i>
                        <div>
                            <strong class="d-block text-dark fs-7">${item.nombre}</strong>
                            <small class="text-muted fs-8">${item.ubicacion || 'Sin ubicación'}</small>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm text-danger border-0 p-1" onclick="eliminarItemCarrito(${item.id}, '${item.tipo}')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `).join('');
        }
    }

    // C) Actualizar estado visual de los botones en las tarjetas del catálogo
    document.querySelectorAll('[data-btn-carrito]').forEach(btn => {
        const id = parseInt(btn.dataset.id);
        const tipo = btn.dataset.tipo;

        const estaEnCarrito = carritoReservas.some(item => item.id === id && item.tipo === tipo);

        if (estaEnCarrito) {
            btn.classList.remove('btn-outline-primary');
            btn.classList.add('btn-success');
            btn.innerHTML = '<i class="fas fa-check"></i> Añadido';
        } else {
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-primary');
            btn.innerHTML = '<i class="fas fa-plus"></i> Seleccionar';
        }
    });
}

// 7. Toggle del menú desplegable del carrito
function toggleDetalleCarrito() {
    const contenido = document.getElementById('carrito-desplegable-contenido');
    const flecha = document.getElementById('flecha-toggle-carrito');

    if (contenido) {
        contenido.classList.toggle('d-none');
        if (flecha) {
            flecha.classList.toggle('fa-chevron-up');
            flecha.classList.toggle('fa-chevron-down');
        }
    }
}