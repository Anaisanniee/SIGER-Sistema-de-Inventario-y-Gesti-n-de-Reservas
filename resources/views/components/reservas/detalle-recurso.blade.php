@props([
    'tipoRecurso'   => 'activo',
    'recursoNombre' => 'Laboratorio de Sistemas A',
    'capacidad'     => 'Personas',
    'serial'        => 'DELL-5420-X92',
    'marca'         => 'Dell Inspiron',
    'activos'       => [],
    'recursos'      => []
])

@php
    $coleccionRecursos = !empty($recursos) ? $recursos : $activos;
    $esMultiple = !empty($coleccionRecursos) && count($coleccionRecursos) > 1;
@endphp

<div class="contenedor-detalle-recurso">
    @if($esMultiple)
        <details class="acordeon-reserva mt-2">
            <summary>
                <span class="resumen-acordeon-info">
                    <i class="bi bi-box-seam text-verde"></i>
                    <span class="resumen-acordeon-texto">Lista de recursos ({{ count($coleccionRecursos) }})</span>
                </span>
                <i class="bi bi-chevron-down icono-flecha"></i>
            </summary>

            <div class="contenido-desplegable mt-2">
                <ul class="resumen-lista-activos">
                    @foreach($coleccionRecursos as $item)
                        @php
                            $tipoItem = is_object($item) 
                                ? ($item->tipo ?? $item->tipo_recurso ?? 'activo') 
                                : (is_array($item) ? ($item['tipo'] ?? 'activo') : 'activo');

                            $nombreItem = is_object($item) 
                                ? ($item->nombre ?? $item->nombres ?? $item->act_nombre ?? $item->aula_nombre ?? 'Recurso') 
                                : (is_array($item) ? ($item['nombre'] ?? $item['nombres'] ?? $item['act_nombre'] ?? $item['aula_nombre'] ?? 'Recurso') : $item);

                            $serialItem = is_object($item) 
                                ? ($item->serial ?? $item->act_serial ?? $item->aula_codigo ?? null) 
                                : (is_array($item) ? ($item['serial'] ?? $item['act_serial'] ?? $item['aula_codigo'] ?? null) : null);

                            $marcaItem = is_object($item) 
                                ? ($item->marca ?? $item->act_marca ?? null) 
                                : (is_array($item) ? ($item['marca'] ?? $item['act_marca'] ?? null) : null);

                            $capacidadItem = is_object($item) 
                                ? ($item->capacidad ?? $item->aula_capacidad ?? null) 
                                : (is_array($item) ? ($item['capacidad'] ?? $item['aula_capacidad'] ?? null) : null);
                        @endphp

                        <li class="resumen-activo-item">
                            <i class="bi {{ $tipoItem === 'aula' ? 'bi-door-open-fill text-verde me-2' : 'bi-check-circle-fill text-verde me-2' }}"></i>
                            <div>
                                <strong>{{ $nombreItem }}</strong>
                                
                                @if($tipoItem === 'aula' || $capacidadItem)
                                    @if($capacidadItem)
                                        <small class="d-block text-muted">Capacidad: {{ $capacidadItem }}</small>
                                    @endif
                                @else
                                    @if($serialItem)
                                        <small class="d-block text-muted">Serial: {{ $serialItem }}</small>
                                    @endif
                                    @if($marcaItem)
                                        <small class="d-block text-muted">Marca: {{ $marcaItem }}</small>
                                    @endif
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </details>
    @else
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
                        <span class="label-atributo">Serial:</span>
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