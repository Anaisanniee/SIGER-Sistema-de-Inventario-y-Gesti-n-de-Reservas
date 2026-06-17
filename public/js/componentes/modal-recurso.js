// public/js/modal-recurso.js
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

            // Campo: Nombre del Recurso
            const elNombre = document.getElementById('ficha-nombre');
            if (elNombre) elNombre.textContent = nombre || 'N/A';

            // Campo: Categoría / Tipo Aula
            const elCategoria = document.getElementById('ficha-categoria');
            if (elCategoria) {
                if (tipo === 'activo') {
                    elCategoria.textContent = (cateId && cateId.trim() !== '') ? cateId : 'N/A';
                } else {
                    elCategoria.textContent = (tipAulaId && tipAulaId.trim() !== '') ? tipAulaId : 'N/A';
                }
            }

            // Campo: Reservable
            const elReservable = document.getElementById('ficha-reservable');
            if (elReservable) {
                if (tipo === 'activo') {
                    elReservable.textContent = (actReservable && actReservable.trim() !== '') ? actReservable : 'No';
                } else {
                    elReservable.textContent = (aulaReservable && aulaReservable.trim() !== '') ? aulaReservable : 'No';
                }
            }


            // =========================================================
            // 4. CONTROL DINÁMICO DE ESPECIFICACIONES (OCULTAR / MOSTRAR)
            // =========================================================
            const bloqueActivo = document.getElementById('bloque-especificaciones-activo');
            const bloqueAula = document.getElementById('bloque-especificaciones-aula');

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

            } else {
                // Rellenar datos exclusivos del Aula
                if (document.getElementById('ficha-capacidad')) document.getElementById('ficha-capacidad').textContent = aulaCapacidad || secundario || 'N/A';
                if (document.getElementById('ficha-estado-aula')) document.getElementById('ficha-estado-aula').textContent = aulaEstado || 'No registra';
                if (document.getElementById('ficha-tipo-aula')) document.getElementById('ficha-tipo-aula').textContent = tipAulaId || 'N/A';

                // Mostrar bloque Aula y ocultar bloque Activo
                if (bloqueActivo) bloqueActivo.style.setProperty('display', 'none', 'important');
                if (bloqueAula) bloqueAula.style.setProperty('grid-template-columns', 'repeat(3, 1fr)', 'important'); 
                if (bloqueAula) bloqueAula.style.setProperty('display', 'grid', 'important');
            }
        }); // <- Aquí se cierra correctamente el evento listener
    }
});