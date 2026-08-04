{{-- resources/views/components/agendas/agenda.blade.php --}}

@props([
    'eventos' => []
])

<div class="tarjeta-blanca-datos p-3 shadow-sm rounded-4 w-100">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h6 class="fw-bold mb-0" style="color: var(--color-texto); font-family: var(--fuente-secundaria);">
                <i class="bi bi-calendar3 me-2" style="color: var(--color-principal);"></i>Agenda de Reservas
            </h6>
            <small style="color: var(--color-texto-secundario); font-size: 0.8rem;">Eventos agendados</small>
        </div>
    </div>

    {{-- Contenedor de FullCalendar --}}
    <div id="calendario-secretaria" style="min-height: 480px;"></div>
</div>

{{-- Estilos encapsulados utilizando únicamente tus variables CSS --}}
<style>
    /* Estilos generales de la barra superior */
    #calendario-secretaria .fc-toolbar {
        gap: 0.5rem;
        margin-bottom: 1.25rem !important;
    }

    /* Título del Mes/Año */
    #calendario-secretaria .fc-toolbar-title {
        color: var(--color-texto) !important;
        font-family: var(--fuente-secundaria) !important;
        font-size: 1.2rem !important;
        font-weight: 700;
        text-transform: capitalize;
    }

    /* Grupos de botones */
    #calendario-secretaria .fc-button-group {
        gap: 0.3rem;
    }

    /* Estilo base para TODOS los botones */
    #calendario-secretaria .fc-button {
        background-color: var(--color-principal) !important;
        border-color: var(--color-principal) !important;
        color: var(--color-fondo) !important;
        font-family: var(--fuente-principal) !important;
        border-radius: var(--borde-radio) !important;
        box-shadow: none !important;
        font-weight: 500;
        padding: 0.375rem 0.75rem;
        text-transform: capitalize;
        transition: all 0.2s ease-in-out;
    }

    /* Estado Hover / Focus */
    #calendario-secretaria .fc-button:hover,
    #calendario-secretaria .fc-button:focus {
        background-color: var(--principal-secundario) !important;
        border-color: var(--principal-secundario) !important;
        color: var(--color-fondo) !important;
    }

    /* Botón Activo (ej: cuando 'Mes' o 'Semana' está seleccionado) */
    #calendario-secretaria .fc-button-primary:not(:disabled).fc-button-active, 
    #calendario-secretaria .fc-button-primary:not(:disabled):active {
        background-color: var(--principal-secundario) !important;
        border-color: var(--principal-secundario) !important;
        color: var(--color-fondo) !important;
        font-weight: 700;
    }

    /* Botón 'Hoy' deshabilitado si ya estás en el día actual */
    #calendario-secretaria .fc-button:disabled {
        background-color: var(--color-fondo-secundario) !important;
        border-color: var(--color-fondo-secundario) !important;
        color: var(--color-texto) !important;
        opacity: 0.7;
    }

    /* Malla y cabeceras del calendario */
    #calendario-secretaria .fc-theme-standard td, 
    #calendario-secretaria .fc-theme-standard th {
        border-color: var(--color-borde) !important;
    }

    #calendario-secretaria .fc-col-header-cell-cushion {
        color: var(--color-texto) !important;
        font-family: var(--fuente-principal) !important;
        font-weight: 600;
        text-decoration: none !important;
    }

    #calendario-secretaria .fc-daygrid-day-number {
        color: var(--color-texto) !important;
        text-decoration: none !important;
        font-weight: 500;
    }

    /* Resaltado suave del día de HOY */
    #calendario-secretaria .fc-day-today {
        background-color: var(--color-verde-pastel) !important;
    }

    /* ESTILO DE EVENTOS COMO BLOQUES/TARJETAS */
    #calendario-secretaria .fc-daygrid-event {
        border-radius: var(--borde-radio) !important;
        padding: 2px 6px !important;
        margin-top: 2px !important;
        border: none !important;
        font-size: 0.78rem !important;
        font-family: var(--fuente-principal) !important;
    }

    #calendario-secretaria .fc-event-main {
        color: var(--color-fondo) !important;
        font-weight: 500;
    }
</style>

{{-- CDN de FullCalendar --}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core/locales/es.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendario-secretaria');
    let eventosPhp = @json($eventos);

    // Mapeo automático de variables CSS
    const rootStyles = getComputedStyle(document.documentElement);
    const colorDisponible = rootStyles.getPropertyValue('--color-estado-disponible').trim() || '#22c55e';
    const colorPendiente = rootStyles.getPropertyValue('--color-estado-en-mantenimiento').trim() || '#facc15';
    const colorDanado = rootStyles.getPropertyValue('--color-estado-dañado').trim() || '#dc2626';
    const colorPrincipal = rootStyles.getPropertyValue('--color-principal').trim() || '#10b981';
    const colorTexto = rootStyles.getPropertyValue('--color-texto').trim() || '#444444';
    const colorFondo = rootStyles.getPropertyValue('--color-fondo').trim() || '#ffffff';

    function obtenerColorEstado(estado) {
        const est = (estado || '').toLowerCase().trim();
        switch (est) {
            case 'aprobado':
            case 'aprobada':
            case 'confirmado':
                return colorDisponible;
            case 'pendiente':
                return colorPendiente;
            case 'rechazado':
            case 'rechazada':
            case 'cancelado':
                return colorDanado;
            default:
                return colorPrincipal;
        }
    }

    const eventosConColor = eventosPhp.map(evt => {
        const estado = evt.extendedProps?.estado || evt.estado || 'pendiente';
        const bg = evt.color || obtenerColorEstado(estado);
        
        return {
            ...evt,
            backgroundColor: bg,
            borderColor: bg,
            // Texto oscuro para pendiente, o color-fondo (blanco) para el resto
            textColor: (estado.toLowerCase() === 'pendiente') ? colorTexto : colorFondo
        };
    });

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        height: 'auto',
        eventDisplay: 'block', // Fuerza a renderizar como pildora/bloque lleno y no como viñeta
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
        },
        buttonText: {
            today:    'Hoy',
            month:    'Mes',
            week:     'Semana'
        },
        events: eventosConColor,
        eventClick: function(info) {
            info.jsEvent.preventDefault();

            const props = info.event.extendedProps || {};
            
            const datosParaModal = {
                id: info.event.id || props.id,
                titulo: info.event.title,
                recurso: props.recurso || info.event.title,
                usuario: props.usuario || 'Docente / Usuario',
                estado: props.estado || 'Pendiente',
                inicio: info.event.start ? info.event.start.toLocaleString() : 'N/A',
                fin: info.event.end ? info.event.end.toLocaleString() : 'N/A'
            };

            if (typeof cargarDatosModal === 'function') {
                cargarDatosModal(datosParaModal);
            }

            const modalEl = document.getElementById('modalgeneral');
            if (modalEl) {
                const modalBs = new bootstrap.Modal(modalEl);
                modalBs.show();
            }
        }
    });

    calendar.render();
});
</script>