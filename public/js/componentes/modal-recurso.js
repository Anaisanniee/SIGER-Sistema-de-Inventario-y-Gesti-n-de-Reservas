document.addEventListener('DOMContentLoaded', function () {
    const modalGeneral = document.getElementById('modalgeneral');

    if (modalGeneral) {
        modalGeneral.addEventListener('show.bs.modal', function (event) {
            const boton = event.relatedTarget; // El botón exacto que presiona el usuario
            if (!boton) return;

            // =========================================================
            // 1. CAPTURA DE ATRIBUTOS (MÉTODO LECTURA NATIVA ESTABLE)
            // =========================================================
            const tipo = boton.getAttribute('data-tipo');
            const nombre = boton.getAttribute('data-nombre');
            const secundario = boton.getAttribute('data-secundario');

            // Aulas: Capacidad, tipo aula id, reservable, estado
            const tipAulaId = boton.getAttribute('data-tip_aula_id');
            const aulaReservable = boton.getAttribute('data-aula_reservable');
            const aulaEstado = boton.getAttribute('data-aula_estado');
            const aulaCapacidad = boton.getAttribute('data-aula_capacidad');

            // Activos: Precio, marca, estado, serial, fecha de ingreso, aula ubicación, reservable, categoría id
            const cateId = boton.getAttribute('data-cate_id'); 
            const actPrecio = boton.getAttribute('data-act_precio') || boton.getAttribute('data-act_precio_actual');
            const actMarca = boton.getAttribute('data-act_marca');
            const actEstado = boton.getAttribute('data-act_estado') || boton.getAttribute('data-act_estado_fisico');
            const actFechaIngreso = boton.getAttribute('data-act_fecha_ingreso');
            const actUbicacion = boton.getAttribute('data-act_ubicacion') || boton.getAttribute('data-aula_nombre');
            const actReservable = boton.getAttribute('data-act_reservable');

            // =========================================================
            // 2. RELLENAR CABECERAS DEL MODAL (HEADER VERDE)
            // =========================================================
            const txtTitulo = modalGeneral.querySelector('.modal-title');
            const txtSubtitulo = modalGeneral.querySelector('.modal-subtitle');
            
            if (txtTitulo) txtTitulo.textContent = nombre || 'Recurso';
            if (txtSubtitulo) {
                txtSubtitulo.textContent = tipo === 'activo' ? 'Serial: ' + secundario : 'Capacidad: ' + secundario;
            }

            // =========================================================
            // 3. ASIGNACIÓN CAMPO POR CAMPO - SECCIÓN IDENTIFICACIÓN
            // =========================================================
            const elNombre = document.getElementById('ficha-nombre');
            if (elNombre) elNombre.textContent = nombre || 'N/A';

            const elCategoria = document.getElementById('ficha-categoria');
            if (elCategoria) {
                if (tipo === 'activo') {
                    elCategoria.textContent = (cateId && cateId.trim() !== '') ? cateId : 'N/A';
                } else {
                    elCategoria.textContent = (tipAulaId && tipAulaId.trim() !== '') ? tipAulaId : 'N/A';
                }
            }

            const elReservable = document.getElementById('ficha-reservable');
            if (elReservable) {
                if (tipo === 'activo') {
                    elReservable.textContent = (actReservable && actReservable.trim() !== '') ? actReservable : 'No';
                } else {
                    elReservable.textContent = (aulaReservable && aulaReservable.trim() !== '') ? aulaReservable : 'No';
                }
            }

            // =========================================================
            // 4. CONTROL DINÁMICO DE ESPECIFICACIONES Y ACORDEÓN
            // =========================================================
            const bloqueActivo = document.getElementById('bloque-especificaciones-activo');
            const bloqueAula = document.getElementById('bloque-especificaciones-aula');
            
            // Capturamos la sección del inventario adicional
            const seccionInventario = document.querySelector('.seccion-activos-asignados');
            const contenedorActivos = document.getElementById('contenedor-activos-dinamicos');
            const badgeConteo = document.getElementById('ficha-conteo-activos');

            if (tipo === 'activo') {
                // Rellenar datos exclusivos del Activo
                if (document.getElementById('ficha-serial')) document.getElementById('ficha-serial').textContent = secundario || 'N/A';
                if (document.getElementById('ficha-marca')) document.getElementById('ficha-marca').textContent = actMarca || 'No registra';
                if (document.getElementById('ficha-estado-activo')) document.getElementById('ficha-estado-activo').textContent = actEstado || 'No registra';
                if (document.getElementById('ficha-fecha')) document.getElementById('ficha-fecha').textContent = actFechaIngreso || 'No registra';
                if (document.getElementById('ficha-aula-nombre')) document.getElementById('ficha-aula-nombre').textContent = actUbicacion || 'No asignada';
                if (document.getElementById('ficha-precio')) document.getElementById('ficha-precio').textContent = actPrecio ? '$' + actPrecio : 'No registra';

                // Mostrar bloque Activo y ocultar bloque Aula
                if (bloqueActivo) bloqueActivo.style.setProperty('display', 'grid', 'important');
                if (bloqueAula) bloqueAula.style.setProperty('display', 'none', 'important');

                // CRUCIAL: Si es un activo, ocultamos por completo el acordeón de inventario
                if (seccionInventario) seccionInventario.style.setProperty('display', 'none', 'important');

            } else {
                // Rellenar datos exclusivos del Aula
                if (document.getElementById('ficha-capacidad')) document.getElementById('ficha-capacidad').textContent = aulaCapacidad || secundario || 'N/A';
                if (document.getElementById('ficha-estado-aula')) document.getElementById('ficha-estado-aula').textContent = aulaEstado || 'No registra';
                if (document.getElementById('ficha-tipo-aula')) document.getElementById('ficha-tipo-aula').textContent = tipAulaId || 'N/A';

                // Mostrar bloque Aula y ocultar bloque Activo
                if (bloqueActivo) bloqueActivo.style.setProperty('display', 'none', 'important');
                if (bloqueAula) bloqueAula.style.setProperty('grid-template-columns', 'repeat(3, 1fr)', 'important'); 
                if (bloqueAula) bloqueAula.style.setProperty('display', 'grid', 'important');

                // Si es un aula, volvemos a mostrar la sección del inventario
                if (seccionInventario) seccionInventario.style.setProperty('display', 'block', 'important');

                // === LOGICA DE INVENTARIO PARA AULAS (Segura sin romper por 'data') ===
                // Nota: Como los datos vienen de los data-attributes del botón, leemos si pasaste un string JSON
                const activosRaw = boton.getAttribute('data-activos');
                let listaActivos = [];

                try {
                    if (activosRaw) {
                        listaActivos = JSON.parse(activosRaw);
                    }
                } catch (e) {
                    console.error("Error al parsear los activos del aula", e);
                }

                // Actualizar el número del badge con la lista real
                if (badgeConteo) badgeConteo.innerText = listaActivos.length;

                // Limpiar la lista previa
                if (contenedorActivos) {
                    contenedorActivos.innerHTML = '';

                    if (listaActivos.length === 0) {
                        contenedorActivos.innerHTML = `
                            <li class="text-muted text-center py-3 fs-7">
                                No hay activos registrados o asignados a esta aula.
                            </li>`;
                    } else {
                        listaActivos.forEach(activo => {
                            contenedorActivos.innerHTML += `
                                <li class="activo-item">
                                    <span><strong>${activo.nombres}</strong></span>
                                    <span class="activo-serial">S/N: ${activo.serial || 'Sin Serial'}</span>
                                </li>
                            `;
                        });
                    }
                }
            }
        }); 
    }
});