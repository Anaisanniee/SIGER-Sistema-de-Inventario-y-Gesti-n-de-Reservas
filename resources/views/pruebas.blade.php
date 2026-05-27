@extends('layouts.app')

@section('mostrarBusqueda', 'false')
@section('content')
    <h1>Pruebas SIGER</h1>
    <p>Esta es una página de pruebas para mostrar los recursos disponibles.</p>

    
<div class = "container-tarjetas">
        @foreach($recursos as $recurso)

            @if(isset($recurso->act_id))

                @component(
                    'components.tarjetas.tarjeta-recurso',

                    [
                        'tipo' => 'activo',
                         
                        'foto' =>
                            $recurso->act_foto
                            ? asset('storage/images/activos/' . $recurso->act_foto)
                            : asset('storage/images/activos/default.jpeg'),




                        'nombre' => $recurso->act_nombre,

                        'etiqueta' => 'Serial',

                        'valor' => $recurso->act_serial,

                        'estado' => $recurso->act_estado_fisico,

                        'reservable' =>
                            $recurso->act_reservable
                            ? 'Sí'
                            : 'No',

                        'actMarca' => $recurso->act_marca,

                        'actFechaIngreso' =>
                            $recurso->act_fecha_ingreso,

                        'actPrecio' =>
                            $recurso->act_precio_actual,

                        'aulaNombre' =>
                            $recurso->aula_nombre,
                    ]
                )

                @endcomponent

            @else

                @component(
                    'components.tarjetas.tarjeta-recurso',

                    [
                        'tipo' => 'aula',

                        'foto' =>
                            $recurso->aula_foto
                            ? asset('storage/images/aulas/' . $recurso->aula_foto)
                            : asset('storage/images/aulas/default.jpeg'),

                        'nombre' => $recurso->aula_nombre,

                        'etiqueta' => 'Capacidad',

                        'valor' =>
                            $recurso->aula_capacidad,

                        'estado' =>
                            $recurso->aula_estado,

                        'reservable' =>
                            $recurso->aula_reservable
                            ? 'Sí'
                            : 'No',

                        'aulaCapacidad' =>
                            $recurso->aula_capacidad,

                        'aulaEstado' =>
                            $recurso->aula_estado,

                        'aulaTipo' =>
                            $recurso->tip_aula_id,
                    ]
                )

                @endcomponent

           @endif

        @endforeach
        <x-modal

            id="modalgeneral"

            title="{{ $nombre ?? $recurso->act_nombre ?? $recurso->aula_nombre ?? 'Recurso' }}"

            subtitle="{{ $etiqueta ?? ''}}: {{ $valor ?? '' }}"

             {{-- CONTROL --}}
             data-tipo="{{ $tipo ?? '' }}"

             {{-- HEADER --}}
             data-nombre="{{ $nombre ?? '' }}"

             data-secundario="{{ $valor ?? '' }}"

             {{-- ACTIVOS --}}
             data-act_id="{{ $recurso->act_id ?? '' }}"

             data-act_marca="{{ $recurso->act_marca ?? 'No registra' }}"

             data-act_estado_fisico="{{ $recurso->act_estado_fisico ?? '' }}"

             data-act_reservable="{{ ($recurso->act_reservable ?? false) ? 'Sí' : 'No' }}"

             data-act_fecha_ingreso="{{ $recurso->act_fecha_ingreso ?? '' }}"

             data-cate_id="{{ $recurso->cate_id ?? '' }}"

             data-aula_nombre="{{ $recurso->aula_nombre ?? 'No asignada' }}"

             data-act_precio_actual="{{ $recurso->precio_actual ?? 'No registra' }}"

             {{-- AULAS --}}
             data-aula_id="{{ $recurso->aula_id ?? '' }}"


             data-aula_capacidad="{{ $recurso->aula_capacidad ?? '' }}"

             data-aula_estado="{{ $recurso->aula_estado ?? '' }}"

             data-aula_reservable="{{ ($recurso->aula_reservable ?? false) ? 'Sí' : 'No' }}"

             data-tip_aula_id="{{ $recurso->tip_aula_id ?? '' }}"

        >

            {{-- CONTENIDO --}}
            @include('components.fichas.ficha-tecnica-universal')

        </x-modal>

</div>
@endsection