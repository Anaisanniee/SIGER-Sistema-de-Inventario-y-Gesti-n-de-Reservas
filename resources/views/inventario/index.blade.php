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
            <h2 class="titulo-pagina"><i class="fas fa-cubes me-2"></i>Gestión de Inventario</h2>
            <p class="subtitulo-pagina">Administra y controla las aulas y activos de la institución en un solo lugar.</p>
        </div>
        
        <div class="acciones-rapidas-panel">
            <x-botones.boton 
                clase="btn-verde"
                url="{{ url('/aulas/crear') }}">
                <i class="fas fa-plus me-1"></i> Nueva Aula
            </x-botones.boton>

            <x-botones.boton 
                clase="btn-verde"
                url="{{ url('/activos/crear') }}">
                <i class="fas fa-plus me-1"></i> Nuevo Activo
            </x-botones.boton>

            <x-botones.boton 
                clase="btn-outline-secundario" 
                url="{{ url('/inventario/papelera') }}">
                <i class="fas fa-trash-alt me-1"></i> Ver Papelera
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

    <!-- CONTENEDOR PRINCIPAL DE TARJETAS -->
    <div class="container-tarjetas">
        @foreach($recursos as $recurso)
            @php
                $esActivo = isset($recurso->act_id);
                $estado = strtolower($esActivo 
                    ? ($recurso->act_estado_fisico ?? $recurso->act_estado ?? '') 
                    : ($recurso->aula_estado ?? '')
                );
                
                $tagEstado = match(true) {
                    str_contains($estado, 'buen') || str_contains($estado, 'excelente') || str_contains($estado, 'disponibl') => 'disponible',
                    str_contains($estado, 'manten') || str_contains($estado, 'regular') => 'en-mantenimiento',
                    str_contains($estado, 'daña') || str_contains($estado, 'malo') => 'dañado',
                    default => 'disponible'
                };

                $strTags = $esActivo ? "activo {$tagEstado}" : "aula {$tagEstado}";
            @endphp

            <div class="tarjeta-wrapper recurso-item" data-tags="{{ $strTags }}">
                @if($esActivo)
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
                @else
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
                @endif
            </div>
        @endforeach

        {{-- MODAL GLOBAL PARA LAS FICHAS TÉCNICAS --}}
        <x-modal id="modalgeneral" title="Cargando..." subtitle="">
            @include('components.fichas.ficha-tecnica-universal')
        </x-modal>
    </div>

    {{-- MODAL DE CONFIRMACIÓN DE ELIMINACIÓN --}}
    <x-modal id="modalConfirmarEliminar" titulo="¿Está seguro de eliminar este recurso?" subtitulo="El elemento se moverá temporalmente a la papelera de recuperación.">
        
        <form id="formEliminarSeguro" action="#" method="POST" class="w-100">
            @csrf
            @method('DELETE')

            <div class="form-group-siger mb-4 text-start">
                <label for="motivo_baja" class="form-label font-weight-bold">
                    Motivo de la Baja <span class="text-danger">*</span>
                </label>
                <input type="text" id="motivo_baja" name="motivo_baja" class="form-control" placeholder="Ej. Daño estructural, obsolescencia, traslado..." required>
            </div>

            <div class="d-flex justify-content-center gap-3 pt-2 w-100">
                <x-botones.boton 
                    type="button" 
                    class="btn btn-secundario"
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

{{-- SCRIPTS --}}
<script>
function prepararEliminacion(id, tipo, nombre, caracteristica) {
    const formulario = document.getElementById('formEliminarSeguro');
    formulario.action = tipo === 'activo' ? `{{ url('/activos') }}/${id}` : `{{ url('/aulas') }}/${id}`;

    const txtTitulo = document.getElementById('modal-titulo-dinamico');
    const txtSubtit = document.getElementById('modal-sub-dinamico');

    if (txtTitulo) txtTitulo.textContent = nombre;
    if (txtSubtit) txtSubtit.textContent = caracteristica;

    const inputMotivo = document.getElementById('motivo_baja');
    if (inputMotivo) inputMotivo.value = '';
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
                        li.className = 'activo-item py-2';
                        li.innerHTML = `
                            <div class="d-flex align-items-center gap-2 border-bottom pb-2">
                                <img src="/storage/${item.act_foto}" class="img-activo-preview">
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

<script src="{{ asset('js/componentes/filtros-inventario.js') }}"></script>
@endsection