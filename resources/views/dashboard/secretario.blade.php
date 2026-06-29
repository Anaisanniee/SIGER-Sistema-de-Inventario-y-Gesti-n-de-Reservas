@extends('layouts.app') 

@section('content')
@section('mostrarRegresar', 'false')

{{--- 1. TARJETA DE BIENVENIDA ---}}
@include('components.tarjetas.tarjeta-bienvenido', ['titulo' => 'Bienvenida secretaria'])

{{--- 2. FILTRO MINIMALISTA OVALADO (COMPONENTE REUTILIZABLE) ---}}
<div class="filtro-rapido-contenedor">
    @include('components.filtros.filtro-rapido', ['opciones' => ['pendiente', 'aceptada', 'rechazada']])
</div>

{{--- 3. CONTENEDOR PRINCIPAL DE TARJETAS DE RECURSOS ---}} 
<div class="container-tarjetas">
    @foreach($recursos as $recurso)

        @if(isset($recurso->act_id))
            
            {{-- TARJETA DE ACTIVO: Corregido 'valor' para que no rompa la línea 19 --}}
            <div class="tarjeta-wrapper" data-tags="{{ isset($recurso->act_estado) ? Str::slug($recurso->act_estado) : 'pendiente' }}">
                @component(
                    'components.tarjetas.tarjeta-recurso',
                    [
                        'tipo' => 'activo',
                        'foto' => $recurso->act_foto ? asset('storage/images/activos/' . $recurso->act_foto) : asset('storage/images/activos/default.jpeg'),
                        'nombre' => $recurso->act_nombre,
                        'etiqueta' => 'Serial',
                        'valor' => $recurso->act_serial,
                        
                        'recurso' => $recurso,
                        'textoBoton' => 'Editar'
                    ]
                )
                @endcomponent
            </div>

        @else

            {{-- TARJETA DE AULA --}}
            <div class="tarjeta-wrapper" data-tags="{{ isset($recurso->aula_estado) ? Str::slug($recurso->aula_estado) : 'pendiente' }}">
                @component(
                    'components.tarjetas.tarjeta-recurso',
                    [
                        'tipo' => 'aula',
                        'foto' => $recurso->aula_foto ? asset('storage/images/aulas/' . $recurso->aula_foto) : asset('storage/images/aulas/default.jpeg'),
                        'nombre' => $recurso->aula_nombre,
                        'etiqueta' => 'Capacidad',
                        'valor' => $recurso->aula_capacidad,
                        
                        'recurso' => $recurso,
                        'textoBoton' => 'Editar'
                        {{---colocar url de papagina d edita solo para secretaria-----}}
                    ]
                )
                @endcomponent
            </div>

       @endif

    @endforeach

    {{--- MODAL GLOBAL PARA VER LAS FICHAS TÉCNICAS ---}}
    <x-modal id="modalgeneral" title="Cargando..." subtitle="">
        @include('components.fichas.ficha-tecnica-universal')
    </x-modal>
</div>

@endsection