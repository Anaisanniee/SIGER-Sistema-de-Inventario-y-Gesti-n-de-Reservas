{{-- resources/views/components/tarjetas/tarjeta-recurso.blade.php --}}

@php
    $esAdmin = isset($esAdmin) ? $esAdmin : false; 

    // Identificamos con precisión quirúrgica el ID y el Tipo exacto
    // Si el objeto tiene un identificador de activo o serial, es ACTIVO. Si tiene capacidad o aula_id, es AULA.
    $esActivo = isset($recurso->act_id) || isset($recurso->act_serial) || isset($recurso->act_marca);
    
    $tipoStr = $esActivo ? 'activo' : 'aula';
    
    // Obtenemos su ID correspondiente
    $idRecurso = $esActivo ? ($recurso->act_id ?? $recurso->id ?? null) : ($recurso->aula_id ?? $recurso->id ?? null);

    // Construimos la URL limpia
    $urlReserva = $idRecurso ? route('reservas.paso1', ['id' => $idRecurso]) . '?tipo=' . $tipoStr : '#';
@endphp

<div class="tarjeta-recurso">

    <img
        src="{{ $foto }}"
        alt="Foto del recurso"
        class="tarjeta-img"
    >

    <div class="tarjeta-body">

        <div class="card-title">
            {{ $nombre }}
        </div>

        <div class="estado">
            {{ $etiqueta }}: {{ $valor }}
        </div>

        <div class="botones-container">

            <x-botones.boton
                class="btn btn-azul"
                target="modalgeneral"

                {{-- CONTROL --}}
                data-tipo="{{ $tipo }}"

                {{-- HEADER --}}
                data-nombre="{{ $nombre }}"
                data-categoria="{{ $recurso->categoria->cate_nombre ?? ($recurso->nombre_tipo_aula_legible ?? 'Sin categoría') }}"
                {{-- AGREGADO: Nuevo atributo exclusivo para aulas que no rompe la lógica actual --}}
                data-tipo-aula="{{ $recurso->categoria->tip_aula_nombre ?? 'Sin categoría' }}"
                data-aula-ubicacion="{{ $recurso->aula->aula_nombre ?? ($recurso->tipo_recurso == 'aula' ? $recurso->aula_nombre : 'No asignado') }}"

                data-secundario="{{ $valor }}"

                {{-- ACTIVOS --}}
                data-act_id="{{ $recurso->act_id ?? '' }}"
                data-act_marca="{{ $recurso->act_marca ?? 'No registra' }}"
                data-act_estado_fisico="{{ $recurso->act_estado_fisico ?? '' }}"
                data-act_reservable="{{ ($recurso->act_reservable ?? false) ? 'Sí' : 'No' }}"
                data-act_fecha_ingreso="{{ $recurso->act_fecha_ingreso ?? '' }}"
                data-cate_id="{{ $recurso->cate_id ?? '' }}"
                data-aula_nombre="{{ $recurso->aula_nombre ?? 'No asignada' }}"
                data-act_precio_actual="{{ $recurso->act_precio_actual ?? 'No registra' }}"

                {{-- AULAS --}}
                data-aula_id="{{ $recurso->aula_id ?? '' }}"
                data-aula_capacidad="{{ $recurso->aula_capacidad ?? '' }}"
                data-aula_estado="{{ $recurso->aula_estado ?? '' }}"
                data-aula_reservable="{{ ($recurso->aula_reservable ?? false) ? 'Sí' : 'No' }}"
                data-activos="{{ isset($recurso->activos) ? json_encode($recurso->activos) : ($recurso->activos_json ?? '[]') }}"
            >Ver ficha</x-botones.boton>

                        {{-- BOTONES ADMINISTRATIVOS --}}
            <div class="botones-admin">

                {{-- BOTÓN DINÁMICO (EDITAR O RESERVAR) --}}
                @if(request()->is('activos*') || request()->is('inventario*'))
                    @php
                        $idEditar = $recurso->act_id ?? $recurso->aula_id ?? null;
                        $rutaEditar = isset($recurso->act_id) ? route('activos.edit', $idEditar) : route('aulas.edit', $idEditar);
                    @endphp

                    <x-botones.boton 
                        class="btn btn-warning" 
                        url="{{ $rutaEditar }}">
                        <i class="bi bi-pencil"></i> Editar
                    </x-botones.boton>
                @else
                    @php
                        // Verificamos de forma estricta qué ID tiene contenido real
                        if (isset($recurso->act_id) && !empty($recurso->act_id)) {
                            $idReserva = $recurso->act_id;
                            $tipoReserva = 'activo';
                        } else {
                            $idReserva = $recurso->aula_id;
                            $tipoReserva = 'aula';
                        }
                    @endphp

                    <x-botones.boton 
                        class="btn btn-primary" 
                        type="button"
                        onclick="window.CarritoReservas.agregar({
                            id: '{{ $idReserva }}',
                            nombre: '{{ addslashes($nombre) }}',
                            secundario: '{{ addslashes($valor) }}',
                            foto: '{{ $foto }}',
                            tipo: '{{ $tipoReserva }}'
                        })">
                        <i class="bi bi-calendar-check"></i> Reservar
                    </x-botones.boton>
                @endif

                {{-- BOTÓN ELIMINAR editar cuando haya controllers--}}
                @if($esAdmin)
                    <x-botones.boton 
                        class="btn btn-rojo" 
                        type="button"
                        data-bs-toggle="modal" 
                        data-bs-target="#modalConfirmarEliminar"
                        onclick="prepararEliminacion('{{ $recurso->act_id ?? $recurso->aula_id }}', '{{ isset($recurso->act_id) ? 'activo' : 'aula' }}')">
                        Eliminar
                    </x-botones.boton>
                @endif

            </div>
            

        </div>
        
    </div>

</div>