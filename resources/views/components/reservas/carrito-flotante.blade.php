<!-- 1. BOTÓN FLOTANTE DEL CARRITO -->
<div id="contenedor-carrito-flotante" class="position-fixed bottom-0 end-0 m-4 z-3" style="display: none;">
    <button id="btn-ver-carrito" type="button" class="btn btn-primary rounded-circle position-relative p-3 shadow-lg d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
        <i class="fas fa-shopping-cart fa-lg"></i>
        <span id="contador-carrito" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
            0
        </span>
    </button>
</div>

<!-- 2. MODAL EXCLUSIVO DEL CARRITO / DETALLE -->
<div class="modal fade" id="modalCarrito" tabindex="-1" aria-labelledby="modalCarritoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="titulo-modal-carrito">
                    <i class="fas fa-list-check me-2"></i>Solicitud de Reserva
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <div id="contenedor-detalle-recursos">
                    <p class="text-muted mb-3 fs-7">
                        A continuación se detallan los recursos seleccionados para la solicitud:
                    </p>
                    
                    <ul id="lista-recursos-carrito" class="list-group list-group-flush border rounded-3 mb-0">
                        {{-- Se inyectan dinámicamente con JS --}}
                    </ul>
                </div>
            </div>

            <div class="modal-footer bg-light">
                <x-botones.boton type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Seguir navegando
                </x-botones.boton>
                <x-botones.boton type="button" class="btn btn-rojo btn-outline-danger" onclick="window.CarritoReservas.limpiar()">
                    <i class="fas fa-trash-alt me-1"></i> Vaciar lista
                </x-botones.boton>
                <button type="button" class="btn btn-success px-4" id="btn-confirmar-solicitud" onclick="window.CarritoReservas.procesarSolicitud()" style="background-color: #2563eb; border-color: #2563eb;">
                    <i class="fas fa-paper-plane me-1"></i> Procesar Solicitud
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    #contenedor-carrito-flotante {
        margin: 1.25rem !important;
    }

    #btn-ver-carrito {
        width: 52px !important;
        height: 52px !important;
        padding: 0 !important;
    }

    #modalCarrito .modal-dialog {
        margin: 0.75rem;
    }

    #modalCarrito .modal-body {
        padding: 1rem !important;
    }

    #modalCarrito .modal-footer {
        flex-direction: column-reverse;
        gap: 0.5rem;
    }

    #modalCarrito .modal-footer > * {
        width: 100% !important;
        margin: 0 !important;
    }
}

@media (max-width: 576px) {
    #contenedor-carrito-flotante {
        margin: 0.85rem !important;
        bottom: 10px !important;
        right: 10px !important;
    }

    #btn-ver-carrito {
        width: 48px !important;
        height: 48px !important;
    }

    #btn-ver-carrito i {
        font-size: 1.1rem;
    }

    #lista-recursos-carrito .list-group-item {
        padding: 0.75rem 0.5rem !important;
    }

    #lista-recursos-carrito h6 {
        font-size: 0.875rem;
        word-break: break-word;
    }

    #lista-recursos-carrito small {
        font-size: 0.75rem;
    }

    #modalCarrito .modal-title {
        font-size: 1.05rem;
    }
}
</style>

<!-- 3. SCRIPT GESTOR GLOBAL DEL CARRITO -->
<script>
    const contextoRuta = window.location.pathname.replace(/[^a-zA-Z0-9]/g, '_');
    const usuarioId = "{{ auth()->check() ? auth()->id() : 'invitado' }}";

    window.CarritoReservas = {
        items: [],
        claveStorage: `siger_carrito_${usuarioId}_${contextoRuta}`,

        init() {
            const guardado = localStorage.getItem(this.claveStorage);
            if (guardado) {
                try {
                    this.items = JSON.parse(guardado) || [];
                } catch (e) {
                    this.items = [];
                }
            } else {
                this.items = [];
            }
            this.actualizarInterfaz();
            
            document.getElementById('btn-ver-carrito')?.addEventListener('click', () => {
                this.abrirModalResumen();
            });

            document.getElementById('btn-confirmar-solicitud')?.addEventListener('click', () => {
                this.procesarSolicitud();
            });
        },

        agregar(recurso) {
            if (!recurso || !recurso.id || !recurso.tipo) return;

            if (recurso.tipo === 'aula') {
                const yaHayAula = this.items.some(item => item.tipo === 'aula');
                if (yaHayAula) {
                    alert('Solo puedes seleccionar máximo un aula por reserva.');
                    return;
                }
            }

            const existe = this.items.some(item => 
                String(item.id) === String(recurso.id) && item.tipo === recurso.tipo
            );

            if (existe) {
                alert('Este recurso ya está agregado en la solicitud.');
                return;
            }

            this.items.push(recurso);
            this.guardar();
            this.actualizarInterfaz();
        },

        eliminar(id, tipo) {
            this.items = this.items.filter(item => !(String(item.id) === String(id) && item.tipo === tipo));
            this.guardar();
            this.actualizarInterfaz();

            if (this.items.length > 0) {
                this.renderizarListaModal();
            } else {
                const modalEl = document.getElementById('modalCarrito');
                if (modalEl) {
                    const bsModal = bootstrap.Modal.getInstance(modalEl);
                    if (bsModal) bsModal.hide();
                }
            }
        },

        limpiar() {
            this.items = [];
            this.guardar();
            this.actualizarInterfaz();

            const modalEl = document.getElementById('modalCarrito');
            if (modalEl) {
                const bsModal = bootstrap.Modal.getInstance(modalEl);
                if (bsModal) bsModal.hide();
            }
        },

        guardar() {
            localStorage.setItem(this.claveStorage, JSON.stringify(this.items));
        },

        procesarSolicitud() {
            if (this.items.length === 0) {
                alert('No hay recursos seleccionados en el carrito.');
                return;
            }

            fetch('/reservas/guardar-seleccion-temporal', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ 
                    items: this.items,
                    ids: this.items.map(i => i.id)
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '/reservas/crear/paso1';
                } else {
                    alert(data.message || 'Hubo un error al procesar la selección.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error de red al intentar procesar la solicitud.');
            });
        },

        actualizarInterfaz() {
            const contenedor = document.getElementById('contenedor-carrito-flotante');
            const contador = document.getElementById('contador-carrito');

            if (contador) contador.innerText = this.items.length;
            if (contenedor) {
                contenedor.style.display = this.items.length > 0 ? 'block' : 'none';
            }
        },

        abrirModalResumen() {
            if (this.items.length === 0) return;
            this.renderizarListaModal();

            const modalEl = document.getElementById('modalCarrito');
            if (modalEl) {
                const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
                bsModal.show();
            }
        },

        renderizarListaModal() {
            const listaContenedor = document.getElementById('lista-recursos-carrito');
            const tituloModal = document.getElementById('titulo-modal-carrito');

            if (tituloModal) {
                tituloModal.innerHTML = this.items.length > 1 
                    ? `<i class="fas fa-layer-group me-2"></i>Solicitud Múltiple (${this.items.length} Recursos)` 
                    : `<i class="fas fa-list-check me-2"></i>Detalle de Reserva`;
            }

            if (listaContenedor) {
                listaContenedor.innerHTML = this.items.map(item => {
                    const esActivo = item.tipo === 'activo';
                    const nombre = item.nombre || item.act_nombre || item.aula_nombre || 'Recurso sin nombre';
                    const secundarioInfo = esActivo 
                        ? 'Serial: ' + (item.secundario || item.serial || item.act_serial || 'Sin Serial')
                        : 'Capacidad: ' + (item.secundario || item.capacidad || item.aula_capacidad || 'N/A');

                    return `
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="badge ${esActivo ? 'bg-primary' : 'bg-info'} p-2 me-3 rounded-circle">
                                    <i class="fas ${esActivo ? 'fa-laptop' : 'fa-door-open'} text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-dark fw-bold">${nombre}</h6>
                                    <small class="text-muted fs-7">${secundarioInfo}</small>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle p-2" 
                                    title="Quitar de la solicitud"
                                    onclick="window.CarritoReservas.eliminar('${item.id}', '${item.tipo}')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </li>
                    `;
                }).join('');
            }
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        window.CarritoReservas.init();
    });
</script>