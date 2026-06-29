@extends('layouts.app') 

@section('content')
@section('mostrarRegresar', 'false')

{{--- 1. TARJETA DE BIENVENIDA ---}}
@include('components.tarjetas.tarjeta-bienvenido', ['titulo' => 'Bienvenida Rectora'])

{{--- 2. FILTRO MINIMALISTA OVALADO DE RECURSOS ---}}
<div class="filtro-rapido-contenedor">
    @include('components.filtros.filtro-rapido', ['opciones' => ['bueno', 'reservable', 'en mantenimiento']])
</div>

{{--- 3. CONTENEDOR PRINCIPAL DE TARJETAS (CON MULTI-TAGS) ---}} 
<div class="container-tarjetas">
    @foreach($recursos as $recurso)

        @if(isset($recurso->act_id))
            
            {{-- Mapeo dinámico acumulativo para Activos (Proyector, laptops, etc.) --}}
            @php
                $tagsActivo = [];
                
                // Si está en buen estado, le agregamos el tag 'bueno'
                if (isset($recurso->act_estado_fisico) && strtolower($recurso->act_estado_fisico) == 'buen estado') {
                    $tagsActivo[] = 'bueno';
                } 
                
                // Si es reservable, le añadimos también ese tag sin borrar el anterior
                if (isset($recurso->act_reservable) && $recurso->act_reservable === true) {
                    $tagsActivo[] = 'reservable';
                }

                // Unimos los tags en una sola cadena separada por espacios ("bueno reservable")
                $strTagsActivo = count($tagsActivo) > 0 ? implode(' ', $tagsActivo) : 'todos';
            @endphp
            
            <div class="tarjeta-wrapper" data-tags="{{ $strTagsActivo }}">
                @component(
                    'components.tarjetas.tarjeta-recurso',
                    [
                        'tipo' => 'activo',
                        'foto' => $recurso->act_foto ? asset('storage/images/activos/' . $recurso->act_foto) : asset('storage/images/activos/default.jpeg'),
                        'nombre' => $recurso->act_nombre,
                        'etiqueta' => 'Serial',
                        'valor' => $recurso->act_serial,
                        'recurso' => $recurso,
                    ]
                )
                @endcomponent
            </div>

        @else

            {{-- Mapeo dinámico acumulativo para Aulas (Laboratorios, salones, etc.) --}}
            @php
                $tagsAula = [];
                
                // Si está disponible, es apta para reservarse
                if (isset($recurso->aula_estado) && strtolower($recurso->aula_estado) == 'disponible') {
                    $tagsAula[] = 'reservable';
                } 
                
                if (isset($recurso->aula_reservable) && $recurso->aula_reservable === true) {
                    $tagsAula[] = 'reservable';
                }

                $strTagsAula = count($tagsAula) > 0 ? implode(' ', $tagsAula) : 'todos';
            @endphp

            <div class="tarjeta-wrapper" data-tags="{{ $strTagsAula }}">
                @component(
                    'components.tarjetas.tarjeta-recurso',
                    [
                        'tipo' => 'aula',
                        'foto' => $recurso->aula_foto ? asset('storage/images/aulas/' . $recurso->aula_foto) : asset('storage/images/aulas/default.jpeg'),
                        'nombre' => $recurso->aula_nombre,
                        'etiqueta' => 'Capacidad',
                        'valor' => $recurso->aula_capacidad,
                        'recurso' => $recurso,
                    ]
                )
                @endcomponent
            </div>

       @endif

    @endforeach

    {{--- MODAL GLOBAL PARA LAS FICHAS TÉCNICAS ---}}
    <x-modal id="modalgeneral" title="Cargando..." subtitle="">
        @include('components.fichas.ficha-tecnica-universal')
    </x-modal>
</div>

@endsection