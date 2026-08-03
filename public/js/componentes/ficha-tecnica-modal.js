document.addEventListener('DOMContentLoaded', function () {
    // 1. Obtener el modal principal por su ID
    const modalGeneral = document.getElementById('modalgeneral');

    if (modalGeneral) {
        // 2. Escuchar el evento oficial de Bootstrap cuando el modal abre
        modalGeneral.addEventListener('show.bs.modal', function (event) {
            
            // Obtener el botón que disparó la apertura del modal
            const boton = event.relatedTarget;
            if (!boton) return;

            // =========================================================
            // 1. CAPTURA DE ATRIBUTOS (Nombres idénticos a tu Blade)
            // =========================================================
            const rawTipo = boton.getAttribute('data-tipo');
            const tipo = rawTipo ? rawTipo.trim().toLowerCase() : 'aula';
            
            const nombre = boton.getAttribute('data-nombre');
            const secundario = boton.getAttribute('data-secundario');

            // Categoría y Ubicación (Coinciden con data-categoria y data-aula-ubicacion)
            const catNombre = boton.getAttribute('data-categoria');
            const aulaUbicacion = boton.getAttribute('data-aula-ubicacion');

            // Datos específicos de Aulas (Coinciden con data-tipo-aula, data-aula_reservable, etc.)
            const tipAulaNombre = boton.getAttribute('data-tipo-aula') || boton.getAttribute('data-tip_aula_nombre');
            const aulaReservable = boton.getAttribute('data-aula_reservable');
            const aulaEstado = boton.getAttribute('data-aula_estado');
            const aulaCapacidad = boton.getAttribute('data-aula_capacidad');

            // Datos específicos de Activos
            const actPrecio = boton.getAttribute('data-act_precio_actual') || boton.getAttribute('data-act_precio');
            const actMarca = boton.getAttribute('data-act_marca');
            const actEstado = boton.getAttribute('data-act_estado_fisico') || boton.getAttribute('data-act_estado');
            const actFechaIngreso = boton.getAttribute('data-act_fecha_ingreso');
            const actReservable = boton.getAttribute('data-act_reservable');

            // =========================================================
            // 2. RELLENAR CABECERAS DEL MODAL (Header)
            // =========================================================
            const txtTitulo = modalGeneral.querySelector('.modal-title');
            const txtSubtitulo = modalGeneral.querySelector('.modal-subtitle');
            
            if (txtTitulo) txtTitulo.textContent = nombre || 'Recurso';
            if (txtSubtitulo) {
                txtSubtitulo.textContent = tipo === 'activo' 
                    ? 'Serial: ' + (secundario || 'N/A') 
                    : 'Capacidad: ' + (secundario || 'N/A');
            }

            // =========================================================
            // 3. ASIGNACIÓN DE CAMPOS COMUNES
            // =========================================================
            const elNombre = document.getElementById('ficha-nombre');
            if (elNombre) elNombre.textContent = nombre || 'N/A';

            const elCategoria = document.getElementById('ficha-categoria');
            if (elCategoria) {
                elCategoria.textContent = (catNombre && catNombre !== 'null' && catNombre.trim() !== '') 
                    ? catNombre 
                    : 'Sin categoría';
            }

            const elAula = document.getElementById('ficha-aula-nombre');
            if (elAula) {
                if (tipo === 'activo') {
                    elAula.textContent = (aulaUbicacion && aulaUbicacion !== 'null' && aulaUbicacion.trim() !== '') 
                        ? aulaUbicacion 
                        : 'No asignado';
                } else {
                    elAula.textContent = 'N/A';
                }
            }

            const elReservable = document.getElementById('ficha-reservable');
            if (elReservable) {
                elReservable.textContent = (tipo === 'activo' ? actReservable : aulaReservable) || 'No';
            }

            // =========================================================
            // 4. CONTROL DINÁMICO DE VISIBILIDAD Y ESPECIFICACIONES
            // =========================================================
            const bloqueActivo = document.getElementById('bloque-especificaciones-activo');
            const bloqueAula = document.getElementById('bloque-especificaciones-aula');
            const seccionInventario = document.querySelector('.seccion-activos-asignados');
            const contenedorActivos = document.getElementById('contenedor-activos-dinamicos');
            const badgeConteo = document.getElementById('ficha-conteo-activos');

            // ---------------------------------------------------------
            // CASO A: ES UN ACTIVO
            // ---------------------------------------------------------
            if (tipo === 'activo') {
                if (document.getElementById('ficha-serial')) document.getElementById('ficha-serial').textContent = secundario || 'N/A';
                if (document.getElementById('ficha-marca')) document.getElementById('ficha-marca').textContent = actMarca || 'No registra';
                if (document.getElementById('ficha-estado-activo')) document.getElementById('ficha-estado-activo').textContent = actEstado || 'No registra';
                if (document.getElementById('ficha-fecha')) document.getElementById('ficha-fecha').textContent = actFechaIngreso || 'No registra';
                if (document.getElementById('ficha-precio')) document.getElementById('ficha-precio').textContent = actPrecio ? '$' + actPrecio : 'No registra';

                // Mostrar únicamente el bloque de activo
                if (bloqueActivo) bloqueActivo.style.setProperty('display', 'grid', 'important');
                if (bloqueAula) bloqueAula.style.setProperty('display', 'none', 'important');
                if (seccionInventario) seccionInventario.style.setProperty('display', 'none', 'important');

            // ---------------------------------------------------------
            // CASO B: ES UN AULA
            // ---------------------------------------------------------
            } else {
                if (document.getElementById('ficha-capacidad')) document.getElementById('ficha-capacidad').textContent = aulaCapacidad || secundario || 'N/A';
                if (document.getElementById('ficha-estado-aula')) document.getElementById('ficha-estado-aula').textContent = aulaEstado || 'No registra';
                
                if (document.getElementById('ficha-tipo-aula')) {
                    document.getElementById('ficha-tipo-aula').textContent = tipAulaNombre || 'N/A';
                }

                // Mostrar bloque de aula y la sección de activos/inventario
                if (bloqueActivo) bloqueActivo.style.setProperty('display', 'none', 'important');
                if (bloqueAula) bloqueAula.style.setProperty('display', 'grid', 'important');
                if (seccionInventario) seccionInventario.style.setProperty('display', 'block', 'important');

                // PARSEAR ACTIVOS (Manejo seguro de caracteres escapados)
                const activosRaw = boton.getAttribute('data-activos');
                let listaActivos = [];
                try {
                    if (activosRaw) {
                        const txtAux = document.createElement('textarea');
                        txtAux.innerHTML = activosRaw;
                        listaActivos = JSON.parse(txtAux.value);
                    }
                } catch (e) { 
                    console.error("Error al parsear activos", e); 
                }

                if (badgeConteo) badgeConteo.innerText = listaActivos.length;
                
                if (contenedorActivos) {
                    contenedorActivos.innerHTML = (Array.isArray(listaActivos) && listaActivos.length > 0)
                        ? listaActivos.map(a => {
                            const nombreAct = a.act_nombre || a.nombres || 'Activo sin nombre';
                            const serialAct = a.act_serial || a.serial || 'Sin Serial';
                            return `<li class="activo-item py-2 px-3 border-bottom d-flex align-items-center justify-content-between">
                                        <span><strong>${nombreAct}</strong></span>
                                        <span class="badge bg-secondary">S/N: ${serialAct}</span>
                                    </li>`;
                          }).join('')
                        : '<li class="text-muted text-center py-3 fs-7">No hay activos asignados.</li>';
                }
            }
        }); 
    }
});