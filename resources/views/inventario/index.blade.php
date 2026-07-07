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

            <x-botones.boton 
                clase="btn-verde" {{-- Usando tu clase verde de SIGER --}}
                url="{{ url('/aulas/crear') }}"> {{-- Cambia por la ruta real cuando la tengan --}}
                <i class="fas fa-plus"></i> Nueva Aula
            </x-botones.boton>

            <x-botones.boton 
                clase="btn-verde" {{-- Mantiene la consistencia con el botón de al lado --}}
                url="{{ url('/activos/crear') }}"> {{-- Cambia por la ruta real cuando la tengan --}}
                <i class="fas fa-plus"></i> Nuevo Activo
            </x-botones.boton>

                        <x-botones.boton 
                clase="btn-papelera" 
                url="{{ url('/inventario/papelera') }}">
                <i class="fas fa-trash-alt" style="margin-right: 5px;"></i> Ver Papelera
            </x-botones.boton>
            
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
                        'textoBoton' => 'Editar',
                        'esAdmin' => true,
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
    <x-modal id="modalConfirmarEliminar" titulo="¿Está seguro de eliminar este recurso?" subtitulo="El elemento se moverá temporalmente a la papelera de recuperación.">
        
        <form id="formEliminarSeguro" action="#" method="POST" style="width: 100%;">
            @csrf
            @method('DELETE')

            {{--- Campo para escribir el motivo de la baja ---}}
            <div class="form-group-siger" style="margin-bottom: 20px; text-align: left;">
                <label for="motivo_baja" style="font-family: var(--fuente-principal); font-weight: 600; color: var(--color-texto); display: block; margin-bottom: 8px;">
                    Motivo de la Baja <span style="color: red;">*</span>
                </label>
                <input type="text" id="motivo_baja" name="motivo_baja" class="form-control" placeholder="Ej. Daño estructural, obsolescencia, traslado..." required style="width: 100%; border-radius: 4px;">
            </div>

            {{--- Contenedor de acciones principales con tus componentes ---}}
            <div class="d-flex justify-content-center gap-3" style="padding-top: 10px; width: 100%;">
                
                {{-- Botón Cancelar usando tu componente --}}
                <x-botones.boton 
                    type="button" 
                    class="btn btn-verde" {{-- Conservando tu estilo verde del 'No, Cancelar' que vi en la captura --}}
                    data-bs-dismiss="modal">
                    No, Cancelar
                </x-botones.boton>
                
                {{-- Botón Confirmar usando tu componente --}}
                <x-botones.boton 
                    type="submit" 
                    class="btn btn-rojo">
                    Sí, Confirmar Baja
                </x-botones.boton>
                
            </div>
        </form>
    </x-modal>

</div> {{-- Cierre correcto de .panel-administracion-contenedor al final de la vista --}}

{{--- SCRIPT PARA ENLAZAR LA TARJETA SELECCIONADA CON EL MODAL ---}}
<script>
function prepararEliminacion(id, tipo, nombre, caracteristica) {
    const formulario = document.getElementById('formEliminarSeguro');
    
    // 1. Modificar la acción del formulario
    if (tipo === 'activo') {
        formulario.action = `{{ url('/activos') }}/${id}`;
    } else {
        formulario.action = `{{ url('/aulas') }}/${id}`;
    }

    // 2. Inyectar el texto usando los nuevos IDs fijos
    const txtTitulo = document.getElementById('modal-titulo-dinamico');
    const txtSubtit = document.getElementById('modal-sub-dinamico');

    if (txtTitulo) txtTitulo.textContent = nombre;
    if (txtSubtit) txtSubtit.textContent = caracteristica;

    // 3. Limpiar el campo del motivo
    const inputMotivo = document.getElementById('motivo_baja');
    if (inputMotivo) {
        inputMotivo.value = '';
    }
}
</script>
<!-- SCRIPT EXCLUSIVO PARA LAS CAJAS KPI Y FILTROS -->
<script src="{{ asset('js/componentes/filtros-inventario.js') }}"></script>
@endsection