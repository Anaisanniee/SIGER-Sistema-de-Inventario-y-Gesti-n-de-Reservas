@extends('layouts.app')

@section('mostrarBusqueda', 'true')
@section('mostrarRegresar', 'true')

@section('content')
{{-- Vinculamos los estilos exclusivos de la vista index --}}
<link rel="stylesheet" href="{{ asset('css/pages/recursos-index.css') }}">

<div class="panel-administracion-contenedor">
    
    <!-- CABECERA DEL PANEL -->
    <div class="cabecera-panel">
        <div class="texto-cabecera">
            <h2 class="titulo-pagina"><i class="fas fa-cubes"></i> Gestión de Inventario</h2>
            <p class="subtitulo-pagina">Administra y controla las aulas y activos de la institución en un solo lugar.</p>
        </div>
        <div class="acciones-rapidas-panel">
            <a href="#" class="btn btn-primary"><i class="fas fa-plus"></i> Nueva Aula</a>
            <a href="#" class="btn btn-success"><i class="fas fa-plus"></i> Nuevo Activo</a>
        </div>
    </div>

    <!-- BLOQUE DE MÉTRICAS / KPIs INTERACTIVOS -->
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

    <!-- 3. COMPONENTE DE FILTRO RÁPIDO (Ubicado justo después de las KPIs) -->
    <div class="contenedor-filtro-rapido-componente">
        @component('components.filtros.filtro-rapido', [
            'opciones' => ['Disponible', 'en Mantenimiento', 'Reservado'],
            'placeholder' => 'Filtrar por estado...'
        ])
        @endcomponent
    </div>

    {{--- 4. CONTENEDOR PRINCIPAL DE TARJETAS ---}} 
    <div class="container-tarjetas">
        @foreach($recursos as $recurso)

            @if(isset($recurso->act_id))
                
                @php
                    // Tag base de tipo
                    $tagsActivo = ['activo'];
                    
                    // Sincroniza con las opciones del componente ('disponible', 'en-mantenimiento', 'reservado')
                    if (isset($recurso->act_estado_fisico) && strtolower($recurso->act_estado_fisico) == 'buen estado') {
                        $tagsActivo[] = 'disponible'; 
                    } 
                    
                    if (isset($recurso->act_estado_fisico) && strtolower($recurso->act_estado_fisico) == 'en mantenimiento') {
                        $tagsActivo[] = 'en-mantenimiento';
                    }

                    if (isset($recurso->act_estado) && strtolower($recurso->act_estado) == 'reservado') {
                        $tagsActivo[] = 'reservado';
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
                        'recurso' => $recurso,
                        'textoBoton' => 'Editar',
                        'esAdmin' => true,  {{-- Indicamos que el usuario es administrador para mostrar el botón de eliminar --}}
                        {{-- url AGREGAR RUTA --}}
                    ])
                    @endcomponent
                </div>

            @else

                @php
                    // Tag base de tipo
                    $tagsAula = ['aula'];
                    
                    // Sincroniza con las opciones del componente ('disponible', 'en-mantenimiento', 'reservado')
                    if (isset($recurso->aula_estado) && strtolower($recurso->aula_estado) == 'disponible') {
                        $tagsAula[] = 'disponible';
                    } 

                    if (isset($recurso->aula_estado) && strtolower($recurso->aula_estado) == 'mantenimiento') {
                        $tagsAula[] = 'en-mantenimiento';
                    }

                    if (isset($recurso->aula_estado) && strtolower($recurso->aula_estado) == 'reservado') {
                        $tagsAula[] = 'reservado';
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
                        'recurso' => $recurso,
                        {{----url AGREGAR RUTA---}}
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
    {{--- MODAL DE CONFIRMACIÓN DE ELIMINACIÓN (PAPELERA DE RECUPERACIÓN) ---}}
    <x-modal id="modalConfirmarEliminar" title="¿Está seguro de eliminar este recurso?" subtitle="El elemento se moverá temporalmente a la papelera de recuperación.">
        <div class="d-flex justify-content-center gap-3" style="padding: 15px 0; width: 100%;">
            
            <!-- Botón de cancelar que simplemente cierra la ventana -->
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 4px; padding: 8px 16px; font-family: var(--fuente-principal); font-weight: 500;">
                No, Cancelar
            </button>
            
            <!-- Formulario dinámico que procesará la baja segura -->
            <form id="formEliminarSeguro" action="#" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" style="background-color: var(--color-rojo, #dc3545); border: none; border-radius: 4px; padding: 8px 16px; font-family: var(--fuente-principal); font-weight: 500; color: white;">
                    Sí, Confirmar Baja
                </button>
            </form>
        </div>
    </x-modal>

</div> {{-- Cierre correcto de .panel-administracion-contenedor al final de la vista --}}

{{--- SCRIPT PARA ENLAZAR LA TARJETA SELECCIONADA CON EL MODAL ---}}
<script>
function prepararEliminacion(id, tipo) {
    // Captura el formulario interno del modal
    const formulario = document.getElementById('formEliminarSeguro');
    
    // Define la ruta exacta de Laravel según si es un aula o un activo
    if (tipo === 'activo') {
        formulario.action = `{{ url('/activos') }}/${id}`;
    } else {
        formulario.action = `{{ url('/aulas') }}/${id}`;
    }
}
</script>

<!-- SCRIPT EXCLUSIVO PARA LAS CAJAS KPI Y FILTROS -->
<script src="{{ asset('js/componentes/filtros-inventario.js') }}"></script>
@endsection