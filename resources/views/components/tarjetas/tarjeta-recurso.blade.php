{{-- resources/views/components/tarjetas/tarjeta-recurso.blade.php --}}

@php
    $esAdmin = isset($esAdmin) ? $esAdmin : false; 

    // Extraemos de forma transparente el ESTADO REAL desde el objeto $recurso
    $estadoReal = $recurso->aula_estado 
        ?? $recurso->act_estado_fisico 
        ?? $recurso->estado 
        ?? '';

    $estadoLimpio = strtolower(trim($estadoReal));

    // Evaluamos la clase según el estado real del objeto
    $claseEstado = match (true) {
        str_contains($estadoLimpio, 'manten') || 
        str_contains($estadoLimpio, 'amnten') || 
        str_contains($estadoLimpio, 'repara')  => 'badge-mantenimiento',

        str_contains($estadoLimpio, 'daña') || 
        str_contains($estadoLimpio, 'ocupad') || 
        str_contains($estadoLimpio, 'inactiv') => 'badge-danado',

        str_contains($estadoLimpio, 'reservad') || 
        str_contains($estadoLimpio, 'prestad') => 'badge-reservado',

        str_contains($estadoLimpio, 'disponibl') || 
        str_contains($estadoLimpio, 'activ')   => 'badge-disponible',

        default => 'badge-disponible',
    };
@endphp

<div class="tarjeta-recurso">

{{-- ETIQUETA EN LA ESQUINA SUPERIOR DERECHA --}}
    <div class="estado-esquina-container">
        <span class="badge-siger-estado {{ $claseEstado }}">
            <i class="fas fa-circle indicador-punto"></i> 
            {{ $valor }}
        </span>
    </div>

    <img
        src="{{ $foto }}"
        alt="Foto del recurso"
        class="tarjeta-img"
    >

    <div class="tarjeta-body">
        <div class="cuerpo-tarjeta">
        <div class="card-title">
            {{ $nombre }}
        </div>

        <div class="estado-tarjeta">
            {{ $etiqueta }}: {{ $valor }}
        </div>
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