document.addEventListener('DOMContentLoaded', function () {
    const modalGeneral = document.getElementById('modalgeneral');

    if (modalGeneral) {
        modalGeneral.addEventListener('show.bs.modal', function (event) {
            const boton = event.relatedTarget;
            if (!boton) return;

            // =========================================================
            // 1. CAPTURA DE ATRIBUTOS
            // =========================================================
            const tipo = boton.getAttribute('data-tipo');
            const nombre = boton.getAttribute('data-nombre');
            const secundario = boton.getAttribute('data-secundario');

            // Atributos de nombres
            const catNombre = boton.getAttribute('data-categoria-nombre');
            const aulaUbicacion = boton.getAttribute('data-aula-ubicacion');

            // Aulas: Capacidad, tipo aula nombre, reservable, estado
            const tipAulaNombre = boton.getAttribute('data-tip_aula_nombre'); // NUEVO: Captura el nombre
            const aulaReservable = boton.getAttribute('data-aula_reservable');
            const aulaEstado = boton.getAttribute('data-aula_estado');
            const aulaCapacidad = boton.getAttribute('data-aula_capacidad');

            // Activos
            const actPrecio = boton.getAttribute('data-act_precio') || boton.getAttribute('data-act_precio_actual');
            const actMarca = boton.getAttribute('data-act_marca');
            const actEstado = boton.getAttribute('data-act_estado') || boton.getAttribute('data-act_estado_fisico');
            const actFechaIngreso = boton.getAttribute('data-act_fecha_ingreso');
            const actReservable = boton.getAttribute('data-act_reservable');

            // =========================================================
            // 2. RELLENAR CABECERAS DEL MODAL
            // =========================================================
            const txtTitulo = modalGeneral.querySelector('.modal-title');
            const txtSubtitulo = modalGeneral.querySelector('.modal-subtitle');
            
            if (txtTitulo) txtTitulo.textContent = nombre || 'Recurso';
            if (txtSubtitulo) {
                txtSubtitulo.textContent = tipo === 'activo' ? 'Serial: ' + secundario : 'Capacidad: ' + secundario;
            }

            // =========================================================
            // 3. ASIGNACIÓN CAMPO POR CAMPO
            // =========================================================
            const elNombre = document.getElementById('ficha-nombre');
            if (elNombre) elNombre.textContent = nombre || 'N/A';

            const elCategoria = document.getElementById('ficha-categoria');
            if (elCategoria) {
                elCategoria.textContent = (catNombre && catNombre !== 'null' && catNombre.trim() !== '') ? catNombre : 'Sin categoría';
            }

            const elAula = document.getElementById('ficha-aula-nombre');
            if (elAula) {
                if (tipo === 'activo') {
                    elAula.textContent = (aulaUbicacion && aulaUbicacion !== 'null' && aulaUbicacion.trim() !== '') ? aulaUbicacion : 'No asignado';
                } else {
                    elAula.textContent = 'N/A';
                }
            }

            const elReservable = document.getElementById('ficha-reservable');
            if (elReservable) {
                elReservable.textContent = (tipo === 'activo' ? actReservable : aulaReservable) || 'No';
            }

            // =========================================================
            // 4. CONTROL DINÁMICO DE ESPECIFICACIONES
            // =========================================================
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
                
                // ASIGNACIÓN CORRECTA DEL NOMBRE DEL TIPO DE AULA
                if (document.getElementById('ficha-tipo-aula')) {
                    document.getElementById('ficha-tipo-aula').textContent = tipAulaNombre || 'N/A';
                }

                if (bloqueActivo) bloqueActivo.style.setProperty('display', 'none', 'important');
                if (bloqueAula) bloqueAula.style.setProperty('display', 'grid', 'important');
                if (seccionInventario) seccionInventario.style.setProperty('display', 'block', 'important');

                const activosRaw = boton.getAttribute('data-activos');
                let listaActivos = [];
                try {
                    if (activosRaw) listaActivos = JSON.parse(activosRaw);
                } catch (e) { console.error("Error al parsear activos", e); }

                if (badgeConteo) badgeConteo.innerText = listaActivos.length;
                if (contenedorActivos) {
                    contenedorActivos.innerHTML = listaActivos.length === 0 ? 
                        '<li class="text-muted text-center py-3 fs-7">No hay activos asignados.</li>' : 
                        listaActivos.map(a => `<li class="activo-item"><span><strong>${a.nombres}</strong></span><span class="activo-serial">S/N: ${a.serial || 'Sin Serial'}</span></li>`).join('');
                }
            }
        }); 
    }
});