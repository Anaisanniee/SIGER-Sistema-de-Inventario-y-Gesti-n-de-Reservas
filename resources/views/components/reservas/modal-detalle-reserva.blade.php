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
                <x-botones.boton type="button" class="btn btn-rojo" data-bs-toggle="modal" data-bs-target="#modalConfirmarRechazo">
                     Rechazar
                </x-botones.boton>
                
                <x-botones.boton type="button" class="btn btn-verde" data-bs-toggle="modal" data-bs-target="#modalConfirmarAprobacion">
                     Aprobar Solicitud
                </x-botones.boton>
            </div>

            {{-- Botón para Revertir --}}
            <div id="bloque-acciones-revertir" style="display: none; width: 100%; justify-content: flex-end;">
                <x-botones.boton type="button" class="btn btn-amarillo" data-bs-toggle="modal" data-bs-target="#modalConfirmarReversion">
                    <i class="fas fa-undo"></i> Revertir a Pendiente
                </x-botones.boton>
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
    function cargarDatosModal(datos) {
        if (!datos) return;

        // Título del modal
        const tituloEl = document.getElementById('modalgeneral-titulo');
        if (tituloEl) tituloEl.innerText = datos.titulo || 'Detalle de Reserva';

        // Bloques de botones de acción
        const bloquePendiente = document.getElementById('bloque-acciones-pendiente');
        const bloqueRevertir = document.getElementById('bloque-acciones-revertir');

        if (bloquePendiente && bloqueRevertir) {
            const estado = (datos.estado || '').toLowerCase().trim();

            // Reset visibilidad
            bloquePendiente.style.setProperty('display', 'none', 'important');
            bloqueRevertir.style.setProperty('display', 'none', 'important');

            // Mostrar bloque según estado
            if (estado === 'pendiente') {
                bloquePendiente.style.setProperty('display', 'flex', 'important');
            } else if (['aprobada', 'rechazada', 'aprobado', 'rechazado'].includes(estado)) {
                bloqueRevertir.style.setProperty('display', 'flex', 'important');
            }
        }
    }
</script>