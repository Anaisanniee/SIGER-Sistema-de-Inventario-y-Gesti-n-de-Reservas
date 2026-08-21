<div class="contenedor-alertas-flotantes" id="contenedorAlertasFlotantes">

    {{-- 1. ERRORES DE VALIDACIÓN --}}
    @if (isset($errors) && $errors->any())
        @foreach ($errors->all() as $error)
            <x-alertas.notificacion tipo="peligro" titulo="Atención" :descartable="true">
                {{ $error }}
            </x-alertas.notificacion>
        @endforeach
    @endif

    {{-- 2. ÉXITO --}}
    @if(session('exito') || session('success'))
        <x-alertas.notificacion tipo="exito" titulo="¡Operación Exitosa!" :descartable="true">
            {{ session('exito') ?? session('success') }}
        </x-alertas.notificacion>
    @endif

    {{-- 3. PELIGRO / ERROR MANUAL --}}
    @if(session('peligro') || session('error') || session('danger'))
        <x-alertas.notificacion tipo="peligro" titulo="Atención" :descartable="true">
            {{ session('peligro') ?? session('error') ?? session('danger') }}
        </x-alertas.notificacion>
    @endif

    {{-- 4. ADVERTENCIA --}}
    @if(session('advertencia') || session('warning') || session('mensaje'))
        <x-alertas.notificacion tipo="advertencia" titulo="Advertencia" :descartable="true">
            {{ session('advertencia') ?? session('warning') ?? session('mensaje') }}
        </x-alertas.notificacion>
    @endif

    {{-- 5. INFORMACIÓN --}}
    @if(session('info'))
        <x-alertas.notificacion tipo="info" titulo="Información" :descartable="true">
            {{ session('info') }}
        </x-alertas.notificacion>
    @endif

</div>

<style>.contenedor-alertas-flotantes {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 99999;
    pointer-events: none; /* No intercepta nada del login */
    display: flex;
    flex-direction: column;
    gap: 12px;
    align-items: center;
    width: 90%;
    max-width: 420px;
    height: 0; /* No tiene alto físico para no descuadrar cajas */
}

/* Permitir clics únicamente en la tarjeta de la alerta y en su botón X */
.contenedor-alertas-flotantes .alerta-siger,
.contenedor-alertas-flotantes .btn-cerrar-alerta {
    pointer-events: auto !important;
    cursor: pointer;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
}</style>

<script>
    (function() {
        function removerAlerta(el) {
            if (!el) return;
            el.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
            el.style.opacity = '0';
            el.style.transform = 'scale(0.85)';
            setTimeout(function() {
                if (el && el.parentNode) {
                    el.parentNode.removeChild(el);
                }
            }, 250);
        }

        // Clic en el botón cerrar (X)
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.btn-cerrar-alerta, [data-btn-cerrar], .fa-times');
            if (btn) {
                var tarjeta = btn.closest('.alerta-siger, [data-alerta-item]');
                if (tarjeta) {
                    e.preventDefault();
                    e.stopPropagation();
                    removerAlerta(tarjeta);
                }
            }
        }, true);

        // Descarte automático en 4 segundos
        setTimeout(function() {
            var tarjetas = document.querySelectorAll('.contenedor-alertas-flotantes .alerta-siger, .contenedor-alertas-flotantes [data-alerta-item]');
            tarjetas.forEach(function(item) {
                removerAlerta(item);
            });
        }, 4000);
    })();
</script>