@extends('layouts.app') 
@section('content')
@section('mostrarRegresar', 'false')

{{---#TARJETA DE BIENVENIDA---}}
@include('components.tarjetas.tarjeta-bienvenido', ['titulo' => 'Bienvenido'])

{{---FILTRO MINIMALISTA PRINCIPAL DE BUSQUEDA RAPIDA PREDERTERMINADA---}}
<div class="filtro-rapido-contenedor">
@include('components.filtros.filtro-rapido', ['opciones' => ['pendientes', 'aceptadas', 'en mantenimiento']])
</div>
{{---#TARJETAS DE RECURSOS---}} 
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
                        'recurso' => $recurso,
                        'textoBoton' => 'Editar'
                        {{---- agregar url caundo haya vista de editar---}}
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
                        'recurso' => $recurso,
                        'textoBoton' => 'Editar'

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

  