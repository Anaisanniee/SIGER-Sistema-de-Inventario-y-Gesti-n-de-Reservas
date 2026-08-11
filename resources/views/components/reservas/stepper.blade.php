@props(['paso'])

@php
    // Calculamos el porcentaje de avance de la línea verde
    $porcentaje = 12;
    if ($paso == 2) $porcentaje = 50;
    if ($paso == 3) $porcentaje = 100;
@endphp

<div class="contenedor-stepper-bloque">
    <div class="contenedor-stepper">
        {{-- Línea gris de fondo fija --}}
        <div class="linea-progreso-fondo"></div>
        
        {{-- Línea verde animada --}}
        <div class="linea-progreso-activa" style="--ancho-final: {{ $porcentaje }}%; width: {{ $porcentaje }}%;"></div>

        {{-- CÍRCULO PASO 1 --}}
        <div class="paso-item {{ $paso == 1 ? 'actual' : '' }} {{ $paso > 1 ? 'completado' : '' }}">
            <div class="paso-circulo {{ $paso >= 1 ? 'activo' : '' }}">
                @if($paso > 1)
                    <i class="bi bi-check-lg"></i>
                @else
                    <span>1</span>
                @endif
            </div>
            <span class="paso-texto">Verificación</span>
        </div>

        {{-- CÍRCULO PASO 2 --}}
        <div class="paso-item {{ $paso == 2 ? 'actual' : '' }} {{ $paso > 2 ? 'completado' : '' }}">
            <div class="paso-circulo {{ $paso >= 2 ? 'activo' : '' }}">
                @if($paso > 2)
                    <i class="bi bi-check-lg"></i>
                @else
                    <span>2</span>
                @endif
            </div>
            <span class="paso-texto">Configuración</span>
        </div>

        {{-- CÍRCULO PASO 3 --}}
        <div class="paso-item {{ $paso == 3 ? 'actual' : '' }}">
            <div class="paso-circulo {{ $paso >= 3 ? 'activo' : '' }}">
                <span>3</span>
            </div>
            <span class="paso-texto">Confirmación</span>
        </div>
    </div>
</div>