@extends('layouts.app') 

@section('content')
@section('mostrarRegresar', 'false')

{{--- 1. TARJETA DE BIENVENIDA ---}}
@include('components.tarjetas.tarjeta-bienvenido', ['titulo' => 'Bienvenida Rectora'])

<div class="contenedor-kpis">
    @component('components.filtros.kpi-selector', [
        'kpis' => [
            ['filtro' => 'todos',  'color' => 'azul',  'icono' => 'fas fa-boxes',     'titulo' => 'Todos',   'subtitulo' => 'Ver todo el inventario'],
            ['filtro' => 'activo', 'color' => 'verde', 'icono' => 'fas fa-tools',     'titulo' => 'Activos', 'subtitulo' => 'Equipos y bienes'],
            ['filtro' => 'aula',   'color' => 'rojo',  'icono' => 'fas fa-door-open', 'titulo' => 'Aulas',   'subtitulo' => 'Espacios físicos']
        ]
    ])
    @endcomponent
</div>

{{--- 3. FILTRO OVALADO DE RECURSOS ---}}
<div class="filtro-rapido-contenedor">
    @include('components.filtros.filtro-rapido', ['opciones' => ['bueno', 'reservable', 'en mantenimiento']])
</div>

{{--- 4. CONTENEDOR PRINCIPAL DE TARJETAS ---}} 
<div class="container-tarjetas">
    @foreach($recursos as $recurso)

        @if(isset($recurso->act_id))
            
            @php
                // Tag base de tipo
                $tagsActivo = ['activo'];
                
                // Limpieza y normalización de textos para evitar fallos por mayúsculas o espacios extra
                $estadoActivo = isset($recurso->act_estado_fisico) ? strtolower(trim($recurso->act_estado_fisico)) : '';
                $reservableActivo = isset($recurso->act_reservable) ? strtolower(trim($recurso->act_reservable)) : '';

                if ($estadoActivo == 'buen estado' || $estadoActivo == 'bueno') {
                    $tagsActivo[] = 'bueno'; 
                } 
                
                if ($estadoActivo == 'en mantenimiento') {
                    // Sincroniza con el filtro rápido: usa espacio o guion según lo requiera tu JavaScript
                    $tagsActivo[] = 'en-mantenimiento'; 
                }

                if ($reservableActivo == 'true' || $recurso->act_reservable === true || $recurso->act_reservable == 1) {
                    $tagsActivo[] = 'reservable';
                }

                $strTagsActivo = implode(' ', $tagsActivo);
            @endphp
            
            <div class="tarjeta-wrapper recurso-item" data-tags="{{ $strTagsActivo }}">
                @component('components.tarjetas.tarjeta-recurso',  [
                    'tipo' => 'activo',
                    'foto' => $recurso->act_foto ? asset('storage/images/activos/' . $recurso->act_foto) : asset('storage/images/activos/default.jpeg'),
                    'nombre' => $recurso->act_nombre,
                    'etiqueta' => 'Serial',
                    'valor' => $recurso->act_serial,
                    'recurso' => $recurso
                ])
                @endcomponent
            </div>

        @else

            @php
                // Tag base de tipo
                $tagsAula = ['aula'];
                
                // Limpieza y normalización de textos
                $estadoAula = isset($recurso->aula_estado) ? strtolower(trim($recurso->aula_estado)) : '';
                $reservableAula = isset($recurso->aula_reservable) ? strtolower(trim($recurso->aula_reservable)) : '';

                // CORRECCIÓN CLAVE: Ahora evalúa correctamente contra 'disponible' en minúsculas
                if ($estadoAula == 'disponible' || $estadoAula == 'bueno') {
                    $tagsAula[] = 'bueno';
                } 

                if ($estadoAula == 'mantenimiento' || $estadoAula == 'en mantenimiento') {
                    $tagsAula[] = 'en-mantenimiento';
                }

                if ($reservableAula == 'true' || $recurso->aula_reservable === true || $recurso->aula_reservable == 1) {
                    $tagsAula[] = 'reservable';
                }

                $strTagsAula = implode(' ', $tagsAula);
            @endphp

            <div class="tarjeta-wrapper recurso-item" data-tags="{{ $strTagsAula }}">
                @component('components.tarjetas.tarjeta-recurso', [
                    'tipo' => 'aula',
                    'foto' => $recurso->aula_foto ? asset('storage/images/aulas/' . $recurso->aula_foto) : asset('storage/images/aulas/default.jpeg'),
                    'nombre' => $recurso->aula_nombre,
                    'etiqueta' => 'Capacidad',
                    'valor' => $recurso->aula_capacidad,
                    'recurso' => $recurso
                ])
                @endcomponent
            </div>

       @endif

    @endforeach

    {{--- MODAL GLOBAL PARA LAS FICHAS TÉCNICAS ---}}
    <x-modal id="modalgeneral" title="Cargando..." subtitle="">
        @include('components.fichas.ficha-tecnica-universal')
    </x-modal>
</div>

<script src="{{ asset('js/componentes/filtros-inventario.js') }}"></script>

@endsection