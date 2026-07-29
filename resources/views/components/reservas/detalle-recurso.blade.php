{{-- resources/views/components/reservas/detalle-recurso.blade.php --}}
@props([
    'tipoRecurso'   => 'activo',
    'recursoNombre' => 'Laboratorio de Sistemas A',
    'capacidad'     => '35 Estudiantes',
    'serial'        => 'DELL-5420-X92',
    'marca'         => 'Dell Inspiron',
    'activos'       => []
])

@php
    $esMultiple = !empty($activos) && count($activos) > 1;
@endphp

<div class="contenedor-detalle-recurso">

    @if($esMultiple)
        {{-- CONDICIÓN A: MÚLTIPLES RECURSOS (Acordeón desplegable) --}}
        <details class="acordeon-reserva mt-2">
            <summary>
                <span class="resumen-acordeon-info">
                    <i class="bi bi-box-seam text-verde"></i>
                    <span class="resumen-acordeon-texto">Lista de activos ({{ count($activos) }})</span>
                </span>
                <i class="bi bi-chevron-down icono-flecha"></i>
            </summary>

            <div class="contenido-desplegable mt-2">
                <ul class="resumen-lista-activos">
                    @foreach($activos as $item)
                        <li class="resumen-activo-item">
                            <i class="bi bi-check-circle-fill text-verde me-2"></i>
                            <div>
                                <strong>{{ is_array($item) ? ($item['nombre'] ?? 'Recurso') : $item }}</strong>
                                @if(is_array($item))
                                    @if(isset($item['serial']))
                                        <small class="d-block text-muted">Serial/Placa: {{ $item['serial'] }}</small>
                                    @elseif(isset($item['capacidad']))
                                        <small class="d-block text-muted">Capacidad: {{ $item['capacidad'] }}</small>
                                    @endif
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </details>

    @else
        {{-- CONDICIÓN B: UN SOLO RECURSO (Ficha/Tarjeta detallada del Paso 1) --}}
        <div class="ficha-tecnica-recurso {{ $tipoRecurso === 'aula' ? 'borde-aula' : 'borde-activo' }}">
            <div class="info-principal-paso1">
                <div class="icono-recurso-grande">
                    <i class="bi {{ $tipoRecurso === 'aula' ? 'bi-door-open-fill' : 'bi-laptop-fill' }}"></i>
                </div>
                <div>
                    <span class="etiqueta-tipo-recurso">{{ $tipoRecurso === 'aula' ? 'Aula / Salón' : 'Activo Tecnológico' }}</span>
                    <h4 class="nombre-recurso-paso1">{{ $recursoNombre }}</h4>
                </div>
            </div>

            <div class="grid-atributos-paso1">
                @if($tipoRecurso === 'aula')
                    <div class="item-atributo">
                        <span class="label-atributo">Capacidad Máxima:</span>
                        <span class="valor-atributo">{{ $capacidad }}</span>
                    </div>
                @else
                    <div class="item-atributo">
                        <span class="label-atributo">Serial / Placa:</span>
                        <span class="valor-atributo font-mono">{{ $serial }}</span>
                    </div>
                    <div class="item-atributo">
                        <span class="label-atributo">Marca:</span>
                        <span class="valor-atributo">{{ $marca }}</span>
                    </div>
                @endif
            </div>
        </div>
    @endif

</div>