@props([
    'esAdmin' => true
])

<!-- MODAL PRINCIPAL GENERAL (RESUMEN DE RESERVA Y ACCIONES) -->
<x-modal id="modalgeneral" titulo="Detalle de la Reserva" subtitulo="Estado de la Solicitud">
    
    <x-reservas.resumen-reserva :mostrarSubtitulo="false" />

    @if($esAdmin)
        <div id="contenedor-acciones-modal" class="d-flex justify-content-between align-items-center w-100 mt-4 pt-3 border-top">
            
            {{-- Botones para Pendiente --}}
            <div id="bloque-acciones-pendiente" style="display: none; width: 100%; gap: 0.75rem;">
                <x-botones.boton type="button" class="btn btn-rojo" data-bs-toggle="modal" data-bs-target="#modalConfirmarRechazo">
                     Rechazar
                </x-botones.boton>
                
                <x-botones.boton type="button" class="btn" data-bs-toggle="modal" data-bs-target="#modalConfirmarAprobacion">
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

<!-- MODALES SECUNDARIOS DE CONFIRMACIÓN -->
@if($esAdmin)
    <!-- MODAL CONFIRMAR RECHAZO -->
    <x-modal id="modalConfirmarRechazo" titulo="Confirmar Acción" subtitulo="Gestión de Reserva">
        <div class="mt-2">
            <x-alertas.notificacion tipo="peligro" titulo="¿Rechazar Solicitud?" :descartable="false">
                La solicitud pasará a estado <strong>Rechazada</strong> y el recurso quedará liberado.
            </x-alertas.notificacion>
        </div>
        <form action="#" method="POST" class="mt-3">
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
                La solicitud pasará a estado <strong>Aprobada</strong>.
            </x-alertas.notificacion>
        </div>
        <form action="#" method="POST" class="mt-3">
            @csrf
            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <x-botones.boton type="button" class="btn" data-bs-dismiss="modal">Cancelar</x-botones.boton>
                <x-botones.boton type="submit" class="btn btn-rojo">Confirmar Aprobación</x-botones.boton>
            </div>
        </form>
    </x-modal>

    <!-- MODAL CONFIRMAR REVERSIÓN -->
    <x-modal id="modalConfirmarReversion" titulo="Confirmar Acción" subtitulo="Gestión de Reserva">
        <div class="mt-2">
            <x-alertas.notificacion tipo="advertencia" titulo="¿Revertir a Pendiente?" :descartable="false">
                La solicitud volverá al estado <strong>Pendiente</strong>.
            </x-alertas.notificacion>
        </div>
        <form action="#" method="POST" class="mt-3">
            @csrf
            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <x-botones.boton type="button" class="btn" data-bs-dismiss="modal">Cancelar</x-botones.boton>
                <x-botones.boton type="submit" class="btn btn-amarillo">Confirmar Reversión</x-botones.boton>
            </div>
        </form>
    </x-modal>
@endif

{{-- JS DENTRO DEL COMPONENTE --}}
<script>
    if (typeof window.cargarDatosModal !== 'function') {
        window.cargarDatosModal = function(datos) {
            const tituloEl = document.getElementById('modalgeneral-titulo');
            if (tituloEl) tituloEl.innerText = datos.titulo || 'Detalle de Reserva';

            const bloquePendiente = document.getElementById('bloque-acciones-pendiente');
            const bloqueRevertir = document.getElementById('bloque-acciones-revertir');

            if (bloquePendiente && bloqueRevertir) {
                const estado = (datos.estado || '').toLowerCase().trim();

                bloquePendiente.style.setProperty('display', 'none', 'important');
                bloqueRevertir.style.setProperty('display', 'none', 'important');

                if (estado === 'pendiente') {
                    bloquePendiente.style.setProperty('display', 'flex', 'important');
                } else if (['aprobada', 'rechazada', 'aprobado', 'rechazado'].includes(estado)) {
                    bloqueRevertir.style.setProperty('display', 'flex', 'important');
                }
            }
        };
    }
</script>