document.addEventListener('DOMContentLoaded', function () {
    const modalGeneral = document.getElementById('modalgeneral');

    if (modalGeneral) {
        modalGeneral.addEventListener('show.bs.modal', function (event) {
            const boton = event.relatedTarget;
            if (!boton) return;

            const rawTipo = boton.getAttribute('data-tipo');
            const tipo = rawTipo ? rawTipo.trim().toLowerCase() : 'aula';
            
            const nombre = boton.getAttribute('data-nombre');
            const secundario = boton.getAttribute('data-secundario');
            const catNombre = boton.getAttribute('data-categoria');
            const aulaUbicacion = boton.getAttribute('data-aula-ubicacion');

            const tipAulaNombre = boton.getAttribute('data-tipo-aula') || boton.getAttribute('data-tip_aula_nombre');
            const aulaReservable = boton.getAttribute('data-aula_reservable');
            const aulaEstado = boton.getAttribute('data-aula_estado');
            const aulaCapacidad = boton.getAttribute('data-aula_capacidad');

            const actPrecio = boton.getAttribute('data-act_precio_actual') || boton.getAttribute('data-act_precio');
            const actMarca = boton.getAttribute('data-act_marca');
            const actEstado = boton.getAttribute('data-act_estado_fisico') || boton.getAttribute('data-act_estado');
            const actFechaIngreso = boton.getAttribute('data-act_fecha_ingreso');
            const actReservable = boton.getAttribute('data-act_reservable');

            // Header del modal
            const txtTitulo = modalGeneral.querySelector('.modal-title');
            const txtSubtitulo = modalGeneral.querySelector('.modal-subtitle');
            
            if (txtTitulo) txtTitulo.textContent = nombre || 'Recurso';
            if (txtSubtitulo) {
                txtSubtitulo.textContent = tipo === 'activo' 
                    ? 'Serial: ' + (secundario || 'N/A') 
                    : 'Capacidad: ' + (secundario || 'N/A');
            }

            // Campos comunes
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
                elAula.textContent = (tipo === 'activo' && aulaUbicacion && aulaUbicacion !== 'null' && aulaUbicacion.trim() !== '') 
                    ? aulaUbicacion 
                    : (tipo === 'activo' ? 'No asignado' : 'N/A');
            }

            const elReservable = document.getElementById('ficha-reservable');
            if (elReservable) {
                elReservable.textContent = (tipo === 'activo' ? actReservable : aulaReservable) || 'No';
            }

            // Bloques de especificaciones
            const bloqueActivo = document.getElementById('bloque-especificaciones-activo');
            const bloqueAula = document.getElementById('bloque-especificaciones-aula');
            const seccionInventario = document.querySelector('.seccion-activos-asignados');
            const contenedorActivos = document.getElementById('contenedor-activos-dinamicos');
            const badgeConteo = document.getElementById('ficha-conteo-activos');

            if (tipo === 'activo') {
                if (document.getElementById('ficha-serial')) document.getElementById('ficha-serial').textContent = secundario || 'N/A';
                if (document.getElementById('ficha-marca')) document.getElementById('ficha-marca').textContent = actMarca || 'No registra';
                if (document.getElementById('ficha-estado-activo')) document.getElementById('ficha-estado-activo').textContent = actEstado || 'No registra';
                if (document.getElementById('ficha-fecha')) document.getElementById('ficha-fecha').textContent = actFechaIngreso || 'No registra';
                if (document.getElementById('ficha-precio')) document.getElementById('ficha-precio').textContent = actPrecio ? '$' + actPrecio : 'No registra';

                if (bloqueActivo) bloqueActivo.style.setProperty('display', 'grid', 'important');
                if (bloqueAula) bloqueAula.style.setProperty('display', 'none', 'important');
                if (seccionInventario) seccionInventario.style.setProperty('display', 'none', 'important');

            } else {
                if (document.getElementById('ficha-capacidad')) document.getElementById('ficha-capacidad').textContent = aulaCapacidad || secundario || 'N/A';
                if (document.getElementById('ficha-estado-aula')) document.getElementById('ficha-estado-aula').textContent = aulaEstado || 'No registra';
                if (document.getElementById('ficha-tipo-aula')) document.getElementById('ficha-tipo-aula').textContent = tipAulaNombre || 'N/A';

                if (bloqueActivo) bloqueActivo.style.setProperty('display', 'none', 'important');
                if (bloqueAula) bloqueAula.style.setProperty('display', 'grid', 'important');
                if (seccionInventario) seccionInventario.style.setProperty('display', 'block', 'important');

                let listaActivos = [];
                const activosRaw = boton.getAttribute('data-activos');
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
                            const imagenAct = a.imagen || a.act_imagen || ''; 

                            return `<li>
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div class="siger-miniatura-container">
                                                <img src="${imagenAct}" alt="Activo">
                                            </div>
                                            <span style="font-size: 0.95rem; font-weight: 500;">${nombreAct}</span>
                                        </div>
                                        <span class="badge bg-secondary" style="font-size: 0.75rem;">S/N: ${serialAct}</span>
                                    </li>`;
                        }).join('')
                        : '<li class="text-muted text-center py-3 fs-7">No hay activos asignados.</li>';
                }
            }
        }); 
    }
});

// --- PARCHE DE SEGURIDAD PARA EL ACORDEÓN DEL MODAL ---
document.addEventListener('click', function (e) {
    const acordeonActivos = e.target.closest('#colapsoActivos');
    if (acordeonActivos) {
        e.stopPropagation();
    }
});