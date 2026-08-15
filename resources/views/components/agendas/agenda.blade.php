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

    {{-- Contenedor de FullCalendar con contención de desborde --}}
    <div id="calendario-secretaria-container" class="w-100 overflow-hidden">
        <div id="calendario-secretaria" style="min-height: 480px;"></div>
    </div>
</div>

{{-- Estilos encapsulados utilizando únicamente variables CSS y reglas responsivas --}}
<style>
    /* =========================================================================
       ESTILOS BASE DE FULLCALENDAR
       ========================================================================= */
    #calendario-secretaria .fc-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem !important;
    }

    #calendario-secretaria .fc-toolbar-title {
        color: var(--color-texto) !important;
        font-family: var(--fuente-secundaria) !important;
        font-size: 1.2rem !important;
        font-weight: 700;
        text-transform: capitalize;
    }

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

    #calendario-secretaria .fc-button:hover,
    #calendario-secretaria .fc-button:focus {
        background-color: var(--principal-secundario) !important;
        border-color: var(--principal-secundario) !important;
        color: var(--color-fondo) !important;
    }

    #calendario-secretaria .fc-button-primary:not(:disabled).fc-button-active, 
    #calendario-secretaria .fc-button-primary:not(:disabled):active {
        background-color: var(--principal-secundario) !important;
        border-color: var(--principal-secundario) !important;
        color: var(--color-fondo) !important;
        font-weight: 700;
    }

    #calendario-secretaria .fc-button:disabled {
        background-color: var(--color-fondo-secundario) !important;
        border-color: var(--color-fondo-secundario) !important;
        color: var(--color-texto) !important;
        opacity: 0.7;
    }

    /* Malla y cabeceras */
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

    #calendario-secretaria .fc-day-today {
        background-color: var(--color-verde-pastel) !important;
    }

    /* Tarjetas/Píldoras de eventos */
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

    /* =========================================================================
       REGLAS RESPONSIVAS (MANTENIENDO TODOS LOS BOTONES)
       ========================================================================= */
    @media (max-width: 576px) {
        #calendario-secretaria .fc-toolbar {
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }

        #calendario-secretaria .fc-toolbar-title {
            text-align: center;
            font-size: 1rem !important;
            order: -1;
        }

        #calendario-secretaria .fc-toolbar-chunk {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        #calendario-secretaria .fc-button {
            padding: 0.25rem 0.4rem !important;
            font-size: 0.75rem !important;
        }

        #calendario-secretaria .fc-col-header-cell-cushion {
            font-size: 0.75rem !important;
        }

        #calendario-secretaria .fc-daygrid-day-number {
            font-size: 0.75rem !important;
        }
    }
</style>

{{-- CDN de FullCalendar --}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core/locales/es.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendario-secretaria');
    let eventosPhp = @json($eventos);

    // Mapeo de variables CSS
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
            textColor: (estado.toLowerCase() === 'pendiente') ? colorTexto : colorFondo
        };
    });

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        height: 'auto',
        handleWindowResize: true,
        eventDisplay: 'block',
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
            
            if (props.modalData) {
                // Creamos un elemento dummy y le asignamos la data del modal exactamente como lo hacen tus tarjetas
                const dummyElement = document.createElement('div');
                
                // Si modalData ya es un objeto, lo convertimos a JSON string para el atributo
                const jsonData = typeof props.modalData === 'object' 
                    ? JSON.stringify(props.modalData) 
                    : props.modalData;

                dummyElement.setAttribute('data-reserva', jsonData);

                if (typeof cargarDatosModalReserva === 'function') {
                    cargarDatosModalReserva(dummyElement);
                }
            }

            // Abrimos el modal de Bootstrap
            const modalEl = document.getElementById('modalgeneral');
            if (modalEl) {
                const modalBs = bootstrap.Modal.getOrCreateInstance(modalEl);
                modalBs.show();
            }
        }
    });

    calendar.render();
});
</script>