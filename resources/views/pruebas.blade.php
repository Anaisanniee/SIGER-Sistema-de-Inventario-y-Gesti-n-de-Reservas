@extends('layouts.app')
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
                            $recurso->precio_actual,

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

            title="Cargando..."

            subtitle="Cargando..."

        >

            {{-- CONTENIDO --}}
            @include('components.fichas.ficha-tecnica-universal')

        </x-modal>

</div>
@endsection