{{-- resources/views/components/tarjetas/tarjeta-recurso.blade.php --}}

@php
    $esAdmin = isset($esAdmin) ? $esAdmin : (Auth::user()->esAdmin ?? false); 

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

    // Variables de apoyo para extraer IDs y nombres dinámicos
    $idRecurso = $recurso->id ?? $recurso->act_id ?? $recurso->aula_id ?? 0;
    $nombreRecurso = $nombre ?? $recurso->nombre ?? $recurso->act_nombre ?? $recurso->aula_nombre ?? 'Recurso sin nombre';
    $ubicacionRecurso = $recurso->ubicacion ?? $recurso->aula_nombre ?? 'Ubicación no especificada';
    $fotoRecurso = $foto ?? $recurso->foto_url ?? asset('storage/images/activos/default.jpeg');
    $tipoRecurso = $tipo ?? ($recurso->tipo_recurso ?? 'activo');
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
        src="{{ $fotoRecurso }}"
        alt="Foto del recurso"
        class="tarjeta-img"
    >

    <div class="tarjeta-body">
        <div class="cuerpo-tarjeta">
            <div class="card-title">
                {{ $nombreRecurso }}
            </div>

            <div class="estado-tarjeta">
                {{ $etiqueta }}: {{ $valor }}
            </div>
        </div>

        <div class="botones-container">

    {{-- BOTÓN VER FICHA (Común para todos) --}}
    <x-botones.boton
        class="btn btn-azul"
        target="modalgeneral"

        {{-- CONTROL --}}
        data-tipo="{{ $tipoRecurso }}"

        {{-- HEADER --}}
        data-nombre="{{ $nombreRecurso }}"
        data-categoria="{{ $recurso->categoria->cate_nombre ?? ($recurso->tipoAula->tip_aula_nombre ?? ($recurso->nombre_tipo_aula_legible ?? 'Sin categoría')) }}"
        data-tipo-aula="{{ $recurso->tipoAula->tip_aula_nombre ?? ($recurso->categoria->cate_nombre ?? 'Sin categoría') }}"
        data-aula-ubicacion="{{ $recurso->aula->aula_nombre ?? ($tipoRecurso == 'aula' ? $recurso->aula_nombre : 'No asignado') }}"
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
        data-activos="{{ e(isset($recurso->activos) ? json_encode($recurso->activos) : ($recurso->activos_json ?? '[]')) }}"
    >Ver ficha</x-botones.boton>
 {{---LOGICA NUEVA EN BOTONES PERO FUNCIONAN --}}
            {{-- BOTONES SEGÚN EL ROL DEL USUARIO --}}
            <div class="botones-admin">

                @if($esAdmin)
                    {{-- ACCIONES DE SECRETARIO / ADMIN --}}
                    
                    {{-- BOTÓN EDITAR --}}
                    <x-botones.boton 
                        class="btn" 
                        url="{{ $urlBoton ?? '/inventario/editar/' . $idRecurso }}">
                        Editar
                    </x-botones.boton>

                    {{-- BOTÓN ELIMINAR --}}
                    <x-botones.boton 
                        class="btn btn-rojo" 
                        type="button"
                        data-bs-toggle="modal" 
                        data-bs-target="#modalConfirmarEliminar"
                        onclick="prepararEliminacion('{{ $recurso->act_id ?? $recurso->aula_id }}', '{{ isset($recurso->act_id) ? 'activo' : 'aula' }}')">
                        Eliminar
                    </x-botones.boton>

                @else
                    {{-- ACCIONES DE DOCENTE / RECTOR / USUARIO GENERAL --}}
                    
                    {{-- BOTÓN RESERVAR (AGREGA AL CARRITO) --}}
                    <x-botones.boton 
                        class="btn" 
                        type="button"
                        onclick="CarritoReservas.agregar({
                            id: '{{ $idRecurso }}',
                            nombre: '{{ addslashes($nombreRecurso) }}',
                            ubicacion: '{{ addslashes($ubicacionRecurso) }}',
                            foto: '{{ $fotoRecurso }}',
                            tipo: '{{ $tipoRecurso }}'
                        })">
                        Reservar
                    </x-botones.boton>

                @endif

            </div>

        </div>
        
    </div>

</div>