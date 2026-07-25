@props([
    'nombre',          {{-- Ej: Computador Dell Inspiron o Laboratorio 1 --}}
    'detalle',         {{-- Ej: #EQ-01 --- Windows 11 o Capacidad: 25 --}}
    'estado' => null   {{-- Opcional, Ej: Disponible --}}
])


<div class="info-item-seleccionado">
    <div class="detalles-equipo">
        <strong>{{ $nombre }}</strong>
        <span>{{ $detalle }}</span>
    </div>
    
    @if($estado)
        <span class="tag-disponibilidad">
            {{ $estado }}
        </span>
    @endif
</div>