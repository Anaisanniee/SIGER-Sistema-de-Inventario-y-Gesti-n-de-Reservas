<!-- ========================================== -->
<!-- 1. BOTÓN FLOTANTE DEL CARRITO              -->
<!-- ========================================== -->
<div id="contenedor-carrito-flotante" class="position-fixed bottom-0 end-0 m-4 z-3" style="display: none;">
    <button id="btn-ver-carrito" type="button" class="btn btn-primary rounded-circle position-relative p-3 shadow-lg d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
        <i class="fas fa-shopping-cart fa-lg"></i>
        <span id="contador-carrito" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
            0
        </span>
    </button>
</div>

<!-- ========================================== -->
<!-- 2. MODAL EXCLUSIVO DEL CARRITO / DETALLE   -->
<!-- ========================================== -->
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
                {{-- Contenedor donde se listan los recursos agregados --}}
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
                <x-botones.boton type="button" class="btn btn-azul btn-success px-4" id="btn-confirmar-solicitud">
                    <i class="fas fa-paper-plane me-1"></i> Procesar Solicitud
                </x-botones.boton>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- 3. SCRIPT GESTOR GLOBAL DEL CARRITO         -->
<!-- ========================================== -->
<script>
    // Identificador único por usuario para no mezclar carritos entre sesiones
    const contextoRuta = window.location.pathname.replace(/[^a-zA-Z0-0]/g, '_');

    // Clave única según la URL en la que estés parado
    const usuarioId = "{{ auth()->check() ? auth()->id() : '' }}";
    const claveFinal = usuarioId ? `usr_${usuarioId}` : `dev_${contextoRuta}`;

    window.CarritoReservas = {
        items: [],
        claveStorage: `siger_carrito_reservas_usr_${usuarioId}`,

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
        },

        agregar(recurso) {
            if (!recurso || !recurso.id) return;

            // Validamos por ID y TIPO (evita colisión entre Aula 1 y Activo 1)
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
                // Si borra todos los items, cierra el modal
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

            // 1. Renderiza los elementos en el HTML del modal
            this.renderizarListaModal();

            // 2. Muestra el modal #modalCarrito
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