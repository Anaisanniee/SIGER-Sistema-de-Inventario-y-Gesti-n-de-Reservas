@extends('layouts.app')

@section('mostrarBusqueda', 'false')
@section('content')
    <h1>Pruebas SIGER</h1>
    <p>Esta es una página de pruebas para mostrar los recursos disponibles.</p>
    

<div class="container-tarjetas">
    @foreach($recursos as $recurso)

        @if(isset($recurso->act_id))

            <div class="tarjeta-wrapper">
                @component(
                    'components.tarjetas.tarjeta-recurso',
                    [
                        'tipo' => 'activo',
                        'foto' => $recurso->act_foto ? asset('storage/images/activos/' . $recurso->act_foto) : asset('storage/images/activos/default.jpeg'),
                        'nombre' => $recurso->act_nombre,
                        'etiqueta' => 'Serial',
                        'valor' => $recurso->act_serial,
                        
                        // 🟢 PASAMOS EL OBJETO COMPLETO PARA QUE EL BOTÓN INTERNO LO LEA
                        'recurso' => $recurso 
                    ]
                )
                @endcomponent
            </div>

        @else

            <div class="tarjeta-wrapper">
                @component(
                    'components.tarjetas.tarjeta-recurso',
                    [
                        'tipo' => 'aula',
                        'foto' => $recurso->aula_foto ? asset('storage/images/aulas/' . $recurso->aula_foto) : asset('storage/images/aulas/default.jpeg'),
                        'nombre' => $recurso->aula_nombre,
                        'etiqueta' => 'Capacidad',
                        'valor' => $recurso->aula_capacidad,
                        
                        // 🟢 LO MISMO PARA EL AULA
                        'recurso' => $recurso
                    ]
                )
                @endcomponent
            </div>

       @endif

    @endforeach

    <x-modal id="modalgeneral" title="Cargando..." subtitle="">
        @include('components.fichas.ficha-tecnica-universal')
    </x-modal>
</div>
@endsection

