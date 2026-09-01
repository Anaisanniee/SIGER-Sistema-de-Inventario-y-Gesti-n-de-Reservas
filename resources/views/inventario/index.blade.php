@extends('layouts.app')

@section('mostrarBusqueda', 'true')
@section('mostrarRegresar', 'true')
@section('rutaRegresar', route('dashboard.secretaria'))

@section('content')

{{-- Vinculamos los estilos exclusivos de la vista index --}}
<link rel="stylesheet" href="{{ asset('css/pages/recursos-index.css') }}">

<div class="panel-administracion-contenedor">
    
    <!-- CABECERA DEL PANEL -->
    <div class="cabecera-panel">
        <div class="texto-cabecera">
            <h2 class="titulo-pagina" style="color: var(--color-principal);"><i class="fas fa-cubes"></i> Gestión de Inventario</h2>
            <p class="subtitulo-pagina">Administra y controla las aulas y activos de la institución en un solo lugar.</p>
        </div>
        <div class="acciones-rapidas-panel">

            <x-botones.boton 
                clase="btn-verde"
                url="{{ url('/aulas/crear') }}">
                <i class="fas fa-plus" style="margin-right: 5px;"></i> Nueva Aula
            </x-botones.boton>

            <x-botones.boton 
                clase="btn-verde"
                url="{{ url('/activos/crear') }}">
                <i class="fas fa-plus" style="margin-right: 5px;"></i> Nuevo Activo
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


    {{--- 4. CONTENEDOR PRINCIPAL DE TARJETAS ---}} 
    <div class="container-tarjetas">
        @foreach($recursos as $recurso)

            @if(isset($recurso->act_id))
                
                @php
                    $tagsActivo = ['activo'];
                    
                    if (isset($recurso->act_estado_fisico) && strtolower($recurso->act_estado_fisico) == 'excelente') {
                        $tagsActivo[] = 'disponible'; 
                    }

                    if (isset($recurso->act_estado_fisico) && strtolower($recurso->act_estado_fisico) == 'bueno') {
                        $tagsActivo[] = 'disponible'; 
                    }
                    if (isset($recurso->act_estado_fisico) && strtolower($recurso->act_estado_fisico) == 'regular') {
                        $tagsActivo[] = 'disponible'; 
                    } 
                    
                    if (isset($recurso->act_estado_fisico) && strtolower($recurso->act_estado_fisico) == 'malo') {
                        $tagsActivo[] = 'en-mantenimiento';
                    }

                    if (isset($recurso->act_estado) && strtolower($recurso->act_estado) == 'dañado') {
                        $tagsActivo[] = 'dañado';
                    }

                    if (isset($recurso->act_estado) && strtolower($recurso->act_estado) == 'malo') {
                        $tagsActivo[] = 'dañado';
                    }

                    $strTagsActivo = implode(' ', $tagsActivo);
                @endphp
                
                <div class="tarjeta-wrapper recurso-item" data-tags="{{ $strTagsActivo }}">
                    @component('components.tarjetas.tarjeta-recurso', [
                        'tipo' => 'activo',
                        'foto' => $recurso->act_foto ? asset('storage/' . $recurso->act_foto) : asset('storage/activos/default.jpeg'),
                        'nombre' => $recurso->act_nombre,
                        'etiqueta' => 'Serial',
                        'valor' => $recurso->act_serial,
                        'categoria' => $recurso->categoria ? $recurso->categoria->cate_nombre : 'Sin categoría',
                        'recurso' => $recurso,
                        'textoBoton' => 'Editar',
                        'esAdmin' => true,
                        'urlBoton' => url('/activos/' . $recurso->act_id . '/editar')
                    ])
                    @endcomponent
                </div>

            @else

                @php
                    $tagsAula = ['aula'];
                    
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
                        'foto' => $recurso->aula_foto ? asset('storage/' . $recurso->aula_foto) : asset('storage/aulas/default.jpeg'),
                        'nombre' => $recurso->aula_nombre,
                        'categoriaNombre' => $recurso->categoria->cat_nombre ?? 'Sin categoría',
                        'etiqueta' => 'Capacidad',
                        'valor' => $recurso->aula_capacidad,
                        'recurso' => $recurso,
                        'textoBoton' => 'Editar',
                        'esAdmin' => true,
                        'urlBoton' => url('/aulas/' . $recurso->aula_id . '/editar')
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

    {{--- MODAL DE CONFIRMACIÓN DE ELIMINACIÓN ---}}
    <x-modal id="modalConfirmarEliminar" titulo="¿Está seguro de eliminar este recurso?" subtitulo="El elemento se moverá temporalmente a la papelera de recuperación.">
        
        <form id="formEliminarSeguro" action="#" method="POST" style="width: 100%;">
            @csrf
            @method('DELETE')

            <div class="form-group-siger" style="margin-bottom: 20px; text-align: left;">
                <label for="motivo_baja" style="font-family: var(--fuente-principal); font-weight: 600; color: var(--color-texto); display: block; margin-bottom: 8px;">
                    Motivo de la Baja <span style="color: red;">*</span>
                </label>
                <input type="text" id="motivo_baja" name="motivo_baja" class="form-control" placeholder="Ej. Daño estructural, obsolescencia, traslado..." required style="width: 100%; border-radius: 4px;">
            </div>

            <div class="d-flex justify-content-center gap-3" style="padding-top: 10px; width: 100%;">
                
                <x-botones.boton 
                    type="button" 
                    class="btn btn-verde"
                    data-bs-dismiss="modal">
                    No, Cancelar
                </x-botones.boton>
                
                <x-botones.boton 
                    type="submit" 
                    class="btn btn-rojo">
                    Sí, Confirmar Baja
                </x-botones.boton>
                
            </div>
        </form>
    </x-modal>

</div>

{{--- SCRIPTS ---}}
<script>
function prepararEliminacion(id, tipo, nombre, caracteristica) {
    const formulario = document.getElementById('formEliminarSeguro');
    
    if (tipo === 'activo') {
        formulario.action = `{{ url('/activos') }}/${id}`;
    } else {
        formulario.action = `{{ url('/aulas') }}/${id}`;
    }

    const txtTitulo = document.getElementById('modal-titulo-dinamico');
    const txtSubtit = document.getElementById('modal-sub-dinamico');

    if (txtTitulo) txtTitulo.textContent = nombre;
    if (txtSubtit) txtSubtit.textContent = caracteristica;

    const inputMotivo = document.getElementById('motivo_baja');
    if (inputMotivo) {
        inputMotivo.value = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const buscador = document.getElementById('buscador-recursos');

    if (buscador) {
        buscador.addEventListener('keyup', function() {
            let filtro = this.value.toLowerCase();
            let tarjetas = document.querySelectorAll('.recurso-item');
            
            tarjetas.forEach(function(tarjeta) {
                let nombre = tarjeta.innerText.toLowerCase();
                tarjeta.style.display = nombre.includes(filtro) ? "" : "none";
            });
        });
    }

    document.querySelectorAll('[data-bs-target="#modalgeneral"]').forEach(button => {
        button.addEventListener('click', function() {
            const contenedor = document.getElementById('contenedor-activos-dinamicos');
            const conteoBadge = document.getElementById('ficha-conteo-activos');
            const fichaCategoria = document.getElementById('ficha-categoria');
            const fichaTipoAula = document.getElementById('ficha-tipo-aula'); 
            const fichaPrecio = document.getElementById('ficha-precio');
            const fichaPrecioMotivo = document.getElementById('ficha-precio-motivo');
            
            const categoria = this.getAttribute('data-categoria') || 'Sin categoría';
            
            if (fichaCategoria) fichaCategoria.textContent = categoria;
            if (fichaTipoAula) fichaTipoAula.textContent = categoria;
            
            const precioActual = this.getAttribute('data-act_precio_actual');
            if (fichaPrecio) {
                if (precioActual && !isNaN(precioActual) && precioActual !== '' && precioActual !== 'null') {
                    const numeroLimpio = parseFloat(precioActual);
                    fichaPrecio.textContent = '$ ' + numeroLimpio.toLocaleString('es-CO', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                } else {
                    fichaPrecio.textContent = 'No registra';
                }
            }

            const motivoPrecio = this.getAttribute('data-act_precio_motivo');
            if (fichaPrecioMotivo) {
                fichaPrecioMotivo.textContent = (motivoPrecio && motivoPrecio !== '' && motivoPrecio !== 'null' && motivoPrecio !== 'undefined') ? motivoPrecio : 'Sin motivo registrado';
            }
            
            if (contenedor) {
                contenedor.innerHTML = '<li class="text-center py-2">Cargando...</li>';
                
                let activos = [];
                try {
                    const data = this.getAttribute('data-activos');
                    if (data) activos = JSON.parse(data);
                } catch (e) {
                    activos = [];
                }

                if (conteoBadge) conteoBadge.textContent = activos.length;
                contenedor.innerHTML = '';
                
                if (Array.isArray(activos) && activos.length > 0) {
                    activos.forEach(item => {
                        const li = document.createElement('li');
                        li.className = 'activo-item text-center py-2';
                        li.innerHTML = `
                            <div style="display: flex; align-items: center; gap: 10px; border-bottom: 1px solid #eee; padding: 5px;">
                                <img src="/storage/${item.act_foto}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                <div>
                                    <strong>${item.act_nombre}</strong><br>
                                    <small class="text-muted">Serial: ${item.act_serial}</small>
                                </div>
                            </div>
                        `;
                        contenedor.appendChild(li);
                    });
                } else {
                    contenedor.innerHTML = '<li class="text-center py-2 text-muted">No hay activos asignados.</li>';
                }
            }
        });
    });

    let alerta = document.getElementById('alerta-mensaje');
    if (alerta) {
        setTimeout(function() {
            alerta.style.transition = "opacity 0.5s ease";
            alerta.style.opacity = "0";
            setTimeout(function() {
                alerta.remove();
            }, 500);
        }, 5000); 
    }
});
</script>

<!-- SCRIPT EXCLUSIVO PARA LAS CAJAS KPI Y FILTROS -->
<script src="{{ asset('js/componentes/filtros-inventario.js') }}"></script>
@endsection