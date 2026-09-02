{{-- resources/views/components/tarjetas/tarjeta-recurso.blade.php --}}

@props([
    'nombre' => 'Recurso',
    'foto' => null,
    'fotoRecurso' => null,
    'etiqueta' => 'Estado',
    'valor' => 'N/A',
    'tipo' => 'activo',
    'recurso' => null,
    'esAdmin' => false
])

@php
    $esAdmin = isset($esAdmin) ? $esAdmin : false; 

    // Definición segura de la foto (previene error si viene vacía o no existe)
    $imagenFinal = $foto ?? $fotoRecurso ?? asset('img/default-recurso.png');

    // Identificamos con precisión si es un activo o un aula
    $esActivo = isset($recurso->act_id) || isset($recurso->act_serial) || isset($recurso->act_marca);
    $tipoStr = $esActivo ? 'activo' : 'aula';
    
    // Obtenemos su ID correspondiente
    $idRecurso = $esActivo ? ($recurso->act_id ?? $recurso->id ?? null) : ($recurso->aula_id ?? $recurso->id ?? null);

    // Extraemos el valor del campo reservable de forma booleana estricta
    $reservableRaw = $recurso->act_reservable ?? $recurso->aula_reservable ?? true;
    
    // Normalizamos valores booleanos, numéricos (0/1) o cadenas de texto ('false', '0', 'no')
    $esReservable = filter_var($reservableRaw, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    if ($esReservable === null) {
        $esReservable = !in_array(strtolower(trim((string)$reservableRaw)), ['false', '0', 'no']);
    }

// Extraemos el estado físico o de aula
    $estadoReal = $recurso->aula_estado 
        ?? $recurso->act_estado_fisico 
        ?? $recurso->estado 
        ?? 'Disponible';

    $estadoLimpio = strtolower(trim($estadoReal));

    // El badge SIEMPRE muestra el estado real para dar visibilidad a Rectora/Secretaria
    $textoBadge = $estadoReal;

    // Evaluamos el color de la lucecita/badge según el estado físico real
    // Si no es reservable por configuración administrativa, también le damos color de alerta si está en buen estado
    if (!$esReservable && !str_contains($estadoLimpio, 'manten') && !str_contains($estadoLimpio, 'daña')) {
        $claseEstado = 'badge-danado'; // Muestra luz roja/bloqueada si fue desactivado manualmente
        $textoBadge = $estadoReal . ' (No reservable)'; // Opcional: aclara entre paréntesis manteniendo el estado
    } else {
        $claseEstado = match (true) {
            str_contains($estadoLimpio, 'en mantenimiento') ||
            str_contains($estadoLimpio, 'manten') ||  
            str_contains($estadoLimpio, 'amnten') || 
            str_contains($estadoLimpio, 'repara')  => 'badge-mantenimiento',
            
            str_contains($estadoLimpio, 'daña') || 
            str_contains($estadoLimpio, 'malo') || 
            str_contains($estadoLimpio, 'inactiv') => 'badge-danado',

            str_contains($estadoLimpio, 'buen') || 
            str_contains($estadoLimpio, 'bun')     => 'badge-reservado',

            str_contains($estadoLimpio, 'disponibl') || 
            str_contains($estadoLimpio, 'activ')   => 'badge-disponible',

            default => 'badge-disponible',
        };
    }

    // Determinamos si la reserva se bloquea
    $estadosBloqueados = ['malo', 'mantenimiento', 'en mantenimiento', 'dañado', 'inactivo', 'reparacion', 'en reparacion'];
    $estaBloqueado = !$esReservable || in_array($estadoLimpio, $estadosBloqueados);
@endphp

<div class="tarjeta-recurso">
    
    {{-- ETIQUETA / LUCECITA EN LA ESQUINA SUPERIOR DERECHA --}}
    <div class="estado-esquina-container">
        <span class="badge-siger-estado {{ $claseEstado }}">
            <i class="fas fa-circle indicador-punto"></i> 
            {{ $textoBadge }}
        </span>
    </div>

    <img
        src="{{ $imagenFinal }}"
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

            {{-- BOTÓN FICHA TÉCNICA --}}
            <x-botones.boton
                class="btn btn-ficha"
                target="modalgeneral"

                {{-- CONTROL --}}
                data-tipo="{{ $tipo }}"

                {{-- HEADER --}}
                data-nombre="{{ $nombre }}"
                data-categoria="{{ $recurso->categoria->cate_nombre ?? ($recurso->nombre_tipo_aula_legible ?? 'Sin categoría') }}"
                data-tipo-aula="{{ $recurso->categoria->tip_aula_nombre ?? 'Sin categoría' }}"
                data-aula-ubicacion="{{ $recurso->aula->aula_nombre ?? ($recurso->tipo_recurso == 'aula' ? $recurso->aula_nombre : 'No asignado') }}"

                data-secundario="{{ $valor }}"

                {{-- ACTIVOS --}}
                data-act_id="{{ $recurso->act_id ?? '' }}"
                data-act_marca="{{ $recurso->act_marca ?? 'No registra' }}"
                data-act_estado_fisico="{{ $recurso->act_estado_fisico ?? '' }}"
                data-act_reservable="{{ $esReservable ? 'Sí' : 'No' }}"
                data-act_fecha_ingreso="{{ $recurso->act_fecha_ingreso ?? '' }}"
                data-cate_id="{{ $recurso->cate_id ?? '' }}"
                data-aula_nombre="{{ $recurso->aula_nombre ?? 'No asignada' }}"
                data-act_precio_actual="{{ $recurso->precioActual->his_pre_valor ?? $recurso->act_precio_actual ?? '' }}"

                {{-- AULAS --}}
                data-aula_id="{{ $recurso->aula_id ?? '' }}"
                data-aula_capacidad="{{ $recurso->aula_capacidad ?? '' }}"
                data-aula_estado="{{ $recurso->aula_estado ?? '' }}"
                data-aula_reservable="{{ $esReservable ? 'Sí' : 'No' }}"
                data-activos="{{ isset($recurso->activos) ? json_encode($recurso->activos) : ($recurso->activos_json ?? '[]') }}"
            >
                Ver ficha
            </x-botones.boton>

            {{-- BOTONES ADMINISTRATIVOS Y DE RESERVA --}}
            <div class="botones-admin">

                {{-- MÓDULO DE EDICIÓN O RESERVA --}}
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
                        if (isset($recurso->act_id) && !empty($recurso->act_id)) {
                            $idReserva = $recurso->act_id;
                            $tipoReserva = 'activo';
                        } else {
                            $idReserva = $recurso->aula_id;
                            $tipoReserva = 'aula';
                        }
                    @endphp

                    @if($estaBloqueado)
                        {{-- Botón deshabilitado si el recurso no es reservable o está en mal estado --}}
                        <x-botones.boton class="btn btn-secondary" type="button" disabled>
                            <i class="bi bi-x-circle"></i> No disponible
                        </x-botones.boton>
                    @else
                        <x-botones.boton 
                            class="btn btn-primary" 
                            type="button"
                            onclick="window.CarritoReservas.agregar({
                                id: '{{ $idReserva ?? '' }}',
                                nombre: '{{ addslashes($nombre) }}',
                                secundario: '{{ addslashes($valor) }}',
                                foto: '{{ $imagenFinal }}',
                                tipo: '{{ $tipoReserva ?? '' }}'
                            })">
                            <i class="bi bi-calendar-check"></i> Reservar
                        </x-botones.boton>
                    @endif
                @endif

                {{-- BOTÓN ELIMINAR --}}
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