@props([
    'esAdmin' => true
])

<!-- =========================================================================
     1. MODAL PRINCIPAL: VER DETALLE DE LA RESERVA
     ========================================================================= -->
<x-modal id="modalgeneral" titulo="Detalle de la Reserva" subtitulo="Estado de la Solicitud">
    
    <x-reservas.resumen-reserva :mostrarSubtitulo="false" />

    @if($esAdmin)
        <div id="contenedor-acciones-modal" class="d-flex justify-content-between align-items-center w-100 mt-4 pt-3 border-top">
            
            {{-- Botones para Estado Pendiente --}}
            <div id="bloque-acciones-pendiente" style="display: none; width: 100%; gap: 0.75rem;">
<<<<<<< HEAD
                <x-botones.boton type="button" class="btn btn-rojo" data-bs-toggle="modal" data-bs-target="#modalConfirmarRechazo">
                     Rechazar
                </x-botones.boton>
                
                <x-botones.boton type="button" class="btn btn-verde" data-bs-toggle="modal" data-bs-target="#modalConfirmarAprobacion">
                     Aprobar Solicitud
                </x-botones.boton>
=======
                <!-- Formulario para Rechazar -->
                <form id="formRechazar" method="POST" style="margin: 0; flex: 1;">
                    @csrf
                    @method('PATCH')
                    <x-botones.boton type="submit" class="btn btn-rojo" style="width: 100%;">
                        Rechazar
                    </x-botones.boton>
                </form>
                
                <!-- Formulario para Aprobar -->
                <form id="formAprobar" method="POST" style="margin: 0; flex: 1;">
                    @csrf
                    @method('PATCH')
                    <x-botones.boton type="submit" class="btn btn-verde" style="width: 100%;">
                        Aprobar Solicitud
                    </x-botones.boton>
                </form>
>>>>>>> origin/backend-Elias
            </div>

            {{-- Botón para Revertir --}}
            <div id="bloque-acciones-revertir" style="display: none; width: 100%; justify-content: flex-end;">
<<<<<<< HEAD
                <x-botones.boton type="button" class="btn btn-amarillo" data-bs-toggle="modal" data-bs-target="#modalConfirmarReversion">
                    <i class="fas fa-undo"></i> Revertir a Pendiente
                </x-botones.boton>
=======
                <!-- Formulario para Revertir -->
                <form id="formRevertir" method="POST" style="margin: 0; width: 100%;">
                    @csrf
                    @method('PATCH')
                    <x-botones.boton type="submit" class="btn btn-amarillo" style="width: 100%; justify-content: center;">
                        <i class="fas fa-undo"></i> Revertir a Pendiente
                    </x-botones.boton>
                </form>
>>>>>>> origin/backend-Elias
            </div>

        </div>
    @endif
</x-modal>

<!-- =========================================================================
     2. MODALES SECUNDARIOS DE CONFIRMACIÓN
     ========================================================================= -->
@if($esAdmin)
    <!-- MODAL CONFIRMAR RECHAZO -->
    <x-modal id="modalConfirmarRechazo" titulo="Confirmar Acción" subtitulo="Gestión de Reserva">
        <div class="mt-2">
            <x-alertas.notificacion tipo="peligro" titulo="¿Rechazar Solicitud?" :descartable="false">
                La solicitud pasará a estado <strong>Rechazada</strong> y el recurso quedará liberado en ese horario si no hay otra reserva activa.
            </x-alertas.notificacion>
        </div>
        <form action="#" method="POST" id="formRechazarReserva" class="mt-3">
            @csrf
            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <x-botones.boton type="button" class="btn" data-bs-dismiss="modal">Cancelar</x-botones.boton>
                <x-botones.boton type="submit" class="btn btn-rojo">Confirmar Rechazo</x-botones.boton>
            </div>
        </form>
    </x-modal>

    <!-- MODAL CONFIRMAR APROBACIÓN -->
    <x-modal id="modalConfirmarAprobacion" titulo="Confirmar Acción" subtitulo="Gestión de Reserva">
        <div class="mt-2">
            <x-alertas.notificacion tipo="exito" titulo="¿Aprobar Solicitud?" :descartable="false">
                La solicitud pasará a estado <strong>Aprobada</strong> y el recurso quedará asignado formalmente al docente en el intervalo de tiempo.
            </x-alertas.notificacion>
        </div>
        <form action="#" method="POST" id="formAprobarReserva" class="mt-3">
            @csrf
            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <x-botones.boton type="button" class="btn" data-bs-dismiss="modal">Cancelar</x-botones.boton>
                <x-botones.boton type="submit" class="btn btn-verde">Confirmar Aprobación</x-botones.boton>
            </div>
        </form>
    </x-modal>

    <!-- MODAL CONFIRMAR REVERSIÓN -->
    <x-modal id="modalConfirmarReversion" titulo="Confirmar Acción" subtitulo="Gestión de Reserva">
        <div class="mt-2">
            <x-alertas.notificacion tipo="advertencia" titulo="¿Revertir a Pendiente?" :descartable="false">
                La solicitud volverá al estado <strong>Pendiente</strong> para ser evaluada nuevamente.
            </x-alertas.notificacion>
        </div>
        <form action="#" method="POST" id="formRevertirReserva" class="mt-3">
            @csrf
            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <x-botones.boton type="button" class="btn" data-bs-dismiss="modal">Cancelar</x-botones.boton>
                <x-botones.boton type="submit" class="btn btn-amarillo">Confirmar Reversión</x-botones.boton>
            </div>
        </form>
    </x-modal>
@endif

<!-- =========================================================================
     3. SCRIPT ENCAPSULADO PARA CARGA DINÁMICA DE BOTONES Y ESTADOS
     ========================================================================= -->
<script>
<<<<<<< HEAD
    function cargarDatosModal(datos) {
        if (!datos) return;

        // Título del modal
        const tituloEl = document.getElementById('modalgeneral-titulo');
        if (tituloEl) tituloEl.innerText = datos.titulo || 'Detalle de Reserva';

        // Bloques de botones de acción
=======
    // CORRECCIÓN: La función recibe el elemento que tiene el data-reserva
    function cargarDatosModal(elemento) {
        const datos = JSON.parse(elemento.getAttribute('data-reserva'));
        if (!datos) return;

        console.log("Datos recibidos en el modal:", datos);

        // 1. Título
        const tituloEl = document.getElementById('modalgeneral-titulo');
        if (tituloEl) tituloEl.innerText = datos.titulo;

        // 2. Mapeo seguro de campos
        const setearTexto = (id, valor) => {
            const el = document.getElementById(id);
            if (el) el.innerText = (valor !== null && valor !== undefined && valor !== '') ? valor : 'N/A';
        };

        setearTexto('resumen-solicitante', datos.solicitante);
        setearTexto('resumen-identificacion', datos.identificacion);
        setearTexto('resumen-email', datos.email);
        setearTexto('resumen-motivo', datos.motivo);
        setearTexto('resumen-fecha-inicio', datos.fechaInicio);
        setearTexto('resumen-hora-inicio', datos.horaInicio);
        setearTexto('resumen-fecha-fin', datos.fechaFin);
        setearTexto('resumen-hora-fin', datos.horaFin);
        setearTexto('resumen-aula-uso', datos.aula);

        // 3. Renderizar la lista de recursos (ACORDEÓN)
        const contenedorRecursos = document.getElementById('resumen-bloque-recurso');
        if (contenedorRecursos && datos.recursos) {
            let htmlRecursos = `
                <h3 class="mb-3" style="font-size: 1.1rem; font-weight: 600; color: #212529;">Recursos Seleccionados (${datos.recursos.length})</h3>
                <div id="contenedor-acordeon-recursos" class="border rounded p-3" style="border-color: #dee2e6 !important; transition: border-color 0.2s ease;">
                    <div style="cursor: pointer; display: flex; justify-content: space-between; align-items: center;" onclick="toggleAcordeonRecursos()">
                        <span style="font-weight: 600; color: #212529;">Lista de recursos (${datos.recursos.length})</span>
                        <i id="icono-acordeon-flecha" class="fas fa-chevron-down" style="transition: transform 0.3s ease;"></i>
                    </div>
                    <div id="acordeon-recursos-body" class="mt-3" style="display: none;">
            `;
            
            datos.recursos.forEach(rec => {
                htmlRecursos += `
                    <div class="border p-3 mb-2 rounded" style="background-color: #ffffff; border-color: #dee2e6 !important; transition: background-color 0.2s ease;"
                         onmouseover="this.style.backgroundColor='#d1e7dd'; this.style.borderColor='#badbcc'" 
                         onmouseout="this.style.backgroundColor='#ffffff'; this.style.borderColor='#dee2e6'">
                        <p class="mb-1" style="font-weight: 600; color: #212529;">${rec.nombre}</p>
                        <p class="mb-1 text-muted" style="font-size: 0.9rem;">Número de serie: ${rec.serial}</p>
                        <p class="mb-0 text-muted" style="font-size: 0.9rem;">Marca: ${rec.marca}</p>
                    </div>
                `;
            });
            
            htmlRecursos += `</div></div>`;
            contenedorRecursos.innerHTML = htmlRecursos;
        }

        // 4. Actualizar rutas y estados (Con los IDs correctos de los formularios)
        const formRechazar = document.getElementById('formRechazar');
        const formAprobar = document.getElementById('formAprobar');
        const formRevertir = document.getElementById('formRevertir');

        if (formRechazar) formRechazar.action = `/secretaria/reservas/${datos.id}/rechazar`;
        if (formAprobar) formAprobar.action = `/secretaria/reservas/${datos.id}/aprobar`;
        if (formRevertir) formRevertir.action = `/secretaria/reservas/${datos.id}/revertir`;

>>>>>>> origin/backend-Elias
        const bloquePendiente = document.getElementById('bloque-acciones-pendiente');
        const bloqueRevertir = document.getElementById('bloque-acciones-revertir');

        if (bloquePendiente && bloqueRevertir) {
<<<<<<< HEAD
            const estado = (datos.estado || '').toLowerCase().trim();

            // Reset visibilidad
            bloquePendiente.style.setProperty('display', 'none', 'important');
            bloqueRevertir.style.setProperty('display', 'none', 'important');

            // Mostrar bloque según estado
=======
            bloquePendiente.style.setProperty('display', 'none', 'important');
            bloqueRevertir.style.setProperty('display', 'none', 'important');

            const estado = (datos.estado || '').toLowerCase().trim();
>>>>>>> origin/backend-Elias
            if (estado === 'pendiente') {
                bloquePendiente.style.setProperty('display', 'flex', 'important');
            } else if (['aprobada', 'rechazada', 'aprobado', 'rechazado'].includes(estado)) {
                bloqueRevertir.style.setProperty('display', 'flex', 'important');
            }
        }
    }
<<<<<<< HEAD
=======

    // Función para el acordeón
    window.toggleAcordeonRecursos = function() {
        const body = document.getElementById('acordeon-recursos-body');
        const flecha = document.getElementById('icono-acordeon-flecha');
        const contenedor = document.getElementById('contenedor-acordeon-recursos');
        
        if (body) {
            const isHidden = body.style.display === 'none';
            body.style.display = isHidden ? 'block' : 'none';
            if (flecha) flecha.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
            if (contenedor) contenedor.style.borderColor = isHidden ? '#198754' : '#dee2e6';
        }
    }
>>>>>>> origin/backend-Elias
</script>