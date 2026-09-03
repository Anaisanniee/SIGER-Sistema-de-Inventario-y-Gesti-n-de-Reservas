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

    $primerElemento = $coleccionRecursos->first() ?? null;
    $dataArray = is_object($primerElemento) ? (array) $primerElemento : ($primerElemento ?? []);
    
    $tipoReal = $dataArray['tipo_recurso_real'] ?? $tipoRecurso;
    $nombreReal = $dataArray['nombre'] ?? $recursoNombre;
    
    // Determinamos si es un aula real de forma robusta
    $esAula = ($tipoReal === 'aula' || str_contains(strtolower($nombreReal), 'aula') || str_contains(strtolower($nombreReal), 'salón') || str_contains(strtolower($tipoRecurso), 'aula'));
    
    // Obtenemos la capacidad final o buscamos si existe en el array
    $capacidadFinal = ($capacidad !== 'Personas' && $capacidad !== 'N/A' && !empty($capacidad)) 
        ? $capacidad 
        : ($dataArray['capacidad'] ?? ($dataArray['aula_capacidad'] ?? 'Por definir'));

    $tipoFinal = $esAula ? 'aula' : $tipoRecurso;
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
                            $itemArr = is_object($item) ? (array) $item : ($item ?? []);
                            $tipoItem = $itemArr['tipo'] ?? ($itemArr['tipo_recurso'] ?? ($itemArr['tipo_recurso_real'] ?? 'activo'));
                            $nombreItem = $itemArr['nombre'] ?? ($itemArr['nombres'] ?? ($itemArr['act_nombre'] ?? ($itemArr['aula_nombre'] ?? 'Recurso')));
                            $serialItem = $itemArr['serial'] ?? ($itemArr['act_serial'] ?? ($itemArr['aula_codigo'] ?? null));
                            $marcaItem = $itemArr['marca'] ?? ($itemArr['act_marca'] ?? null);
                            $capacidadItem = $itemArr['capacidad'] ?? ($itemArr['aula_capacidad'] ?? null);
                            $esItemAula = ($tipoItem === 'aula' || str_contains(strtolower($nombreItem), 'aula') || str_contains(strtolower($nombreItem), 'salón'));
                        @endphp

                        <li class="resumen-activo-item">
                            <i class="bi {{ $esItemAula || $capacidadItem ? 'bi-door-open-fill' : 'bi-laptop-fill' }}"></i>
                            <div>
                                <strong>{{ $nombreItem }}</strong>
                                
                                @if($esItemAula || $capacidadItem)
                                    <small class="d-block text-muted">Capacidad: {{ $capacidadItem ?? 'Por definir' }}</small>
                                @else
                                    @if($serialItem && $serialItem !== 'N/A')
                                        <small class="d-block text-muted">Serial: {{ $serialItem }}</small>
                                    @endif
                                    @if($marcaItem && $marcaItem !== 'N/A')
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
        <div class="ficha-tecnica-recurso {{ $tipoFinal === 'aula' ? 'borde-aula' : 'borde-activo' }}">
            <div class="info-principal-paso1">
                <div class="icono-recurso-grande">
                    <i class="bi {{ $tipoFinal === 'aula' ? 'bi-door-open-fill' : 'bi-laptop-fill' }}"></i>
                </div>
                <div>
                    <span class="etiqueta-tipo-recurso">{{ $tipoFinal === 'aula' ? 'Aula / Salón' : 'Activo Tecnológico' }}</span>
                    <h4 class="nombre-recurso-paso1">{{ $nombreReal }}</h4>
                </div>
            </div>

            <div class="grid-atributos-paso1">
                @if($tipoFinal === 'aula')
                    <div class="item-atributo">
                        <span class="label-atributo">Capacidad Máxima:</span>
                        <span class="valor-atributo">{{ $capacidadFinal }}</span>
                    </div>
                @else
                    <div class="item-atributo">
                        <span class="label-atributo">Serial:</span>
                        <span class="valor-atributo font-mono">{{ $dataArray['serial'] ?? $serial }}</span>
                    </div>
                    <div class="item-atributo">
                        <span class="label-atributo">Marca:</span>
                        <span class="valor-atributo">{{ $dataArray['marca'] ?? $marca }}</span>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>