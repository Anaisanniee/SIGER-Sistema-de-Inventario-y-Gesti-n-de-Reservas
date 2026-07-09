{{-- resources/views/components/tarjetas/tarjeta-recurso.blade.php --}}

@php
    // Si la variable fue enviada desde la vista, usa ese valor. Si no, la pone en false por defecto.
    $esAdmin = isset($esAdmin) ? $esAdmin : false; 
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

            {{-- BOTÓN FICHA --}}
            <x-botones.boton

                class="btn btn-azul"

                target="modalgeneral"



                {{-- CONTROL --}}
                data-tipo="{{ $tipo }}"

                {{-- HEADER --}}
                data-nombre="{{ $nombre }}"
                data-categoria-nombre="{{ $recurso->categoria->cate_nombre ?? 'Sin categoría' }}"
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

                data-tip_aula_nombre="{{ $recurso->nombre_tipo_aula_legible ?? 'No especificado' }}"

                data-categoria-nombre="{{ $recurso->nombre_tipo_aula_legible ?? 'Sin categoría' }}"
                
                data-aula_capacidad="{{ $recurso->aula_capacidad ?? '' }}"

                data-aula_estado="{{ $recurso->aula_estado ?? '' }}"

                data-aula_reservable="{{ ($recurso->aula_reservable ?? false) ? 'Sí' : 'No' }}"

                data-tip_aula_id="{{ $recurso->tip_aula_id ?? '' }}"

                data-activos="{{ $recurso->activos_json }}"

            >Ver ficha</x-botones.boton>

                        {{-- BOTONES ADMINISTRATIVOS --}}
            <div class="botones-admin">

                {{-- BOTÓN EDITAR --}}
                <x-botones.boton 
                    class="btn" 
                    url="{{ $urlBoton ?? '/reservas' }}">
                    {{ $textoBoton ?? 'Reservar' }}
                </x-botones.boton>

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