@extends('layouts.app') 
@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'false')
@section('mostrarBusqueda', 'true')

@section('content')
@php
    $esAdmin = Auth::check() && in_array(Auth::user()->rol, ['admin', 'secretario', 'secretaria']);
@endphp
<link rel="stylesheet" href="{{ asset('css/pages/dashboard-secretario.css') }}">
{{-- Resto del código... --}}

{{--- 1. TARJETA DE BIENVENIDA ---}}
@include('components.tarjetas.tarjeta-bienvenido', [
    'titulo' => 'Bienvenido Rector',
    'descripcion' => 'Consulta informes de inventario y reservas de la Institución Educativa Bohórquez aquí'
])

<div class="dashboard-columna">
    <h3 class="dashboard-subtitulo">Módulos Disponibles</h3>
    <div class="contenedor-accesos">
        <x-tarjetas.tarjeta-acceso-rapido 
            href="#"
            icono="fas fa-boxes"
            claseAcceso="acceso-inventario"
            titulo="Informes Inventario"
            descripcion="Consulta y genera informes detallados sobre el inventario."
        />

        <x-tarjetas.tarjeta-acceso-rapido 
            href="#"
            icono="fas fa-calendar-alt"
            claseAcceso="acceso-reservas"
            titulo="Informes Reservas"
            descripcion="Consulta y genera informes detallados sobre las reservas."
        />
    </div>
</div>

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

                if ($estadoActivo == 'buen estado' || $estadoActivo == 'excelente') {
                    $tagsActivo[] = 'bueno'; 
                } 

                if ($estadoActivo == 'buen estado' || $estadoActivo == 'regular') {
                    $tagsActivo[] = 'bueno'; 
                } 
                
                if ($estadoActivo == 'malo' || $estadoActivo == 'malo') {
                    $tagsActivo[] = 'en-mantenimiento'; 
                }

                if ($reservableActivo == 'true' || $recurso->act_reservable === true || $recurso->act_reservable == 1) {
                    $tagsActivo[] = 'reservable';
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
                    'estado' => $recurso->act_estado ?? 'Desconocido',
                    'categoria' => $recurso->categoria ? $recurso->categoria->cate_nombre : 'Sin categoría',
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
                    'foto' => $recurso->aula_foto ? asset('storage/' . $recurso->aula_foto) : asset('storage/aulas/default.jpeg'),
                    'nombre' => $recurso->aula_nombre,
                    'etiqueta' => 'Capacidad',
                    'estado' => $recurso->aula_estado ?? 'Desconocido',
                    'valor' => $recurso->aula_capacidad,
                    'recurso' => $recurso
                ])
                @endcomponent
            </div>

        @endif

    @endforeach

    <x-modal id="modalgeneral" title="Cargando..." subtitle="">
        @include('components.fichas.ficha-tecnica-universal')
    </x-modal>
</div>
<x-reservas.carrito-flotante/>

<script>
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

        buscador.closest('form')?.addEventListener('submit', function(e) {
            if (document.querySelector('.container-tarjetas')) {
                e.preventDefault(); 
                return false;
            }
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
            
            const categoriaGenerica = this.getAttribute('data-categoria');
            const tipoAulaEspecifico = this.getAttribute('data-tipo-aula');
            const tipoRecurso = this.getAttribute('data-tipo');
            
            if (fichaCategoria) fichaCategoria.textContent = (tipoRecurso === 'aula') ? (tipoAulaEspecifico || 'Sin categoría') : (categoriaGenerica || 'Sin categoría');
            if (fichaTipoAula) fichaTipoAula.textContent = (tipoRecurso === 'aula') ? (tipoAulaEspecifico || 'Sin categoría') : (categoriaGenerica || 'Sin categoría');
            
            const precioActual = this.getAttribute('data-act_precio_actual');
            if (fichaPrecio) {
                if (precioActual && !isNaN(precioActual) && precioActual !== '' && precioActual !== 'null') {
                    fichaPrecio.textContent = Number(precioActual).toLocaleString('es-CO', {
                        style: 'currency',
                        currency: 'COP'
                    });
                } else {
                    fichaPrecio.textContent = 'No registra';
                }
            }

            const motivoPrecio = this.getAttribute('data-act_precio_motivo');
            if (fichaPrecioMotivo) {
                fichaPrecioMotivo.textContent = (motivoPrecio && motivoPrecio.trim() !== '' && motivoPrecio !== 'null' && motivoPrecio !== 'undefined') ? motivoPrecio : 'Sin motivo registrado';
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
                        li.className = 'activo-item';
                        li.innerHTML = `
                            <div class="activo-card-siger" style="display: flex; align-items: center; gap: 10px; border-bottom: 1px solid #eee; padding: 5px;">
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
});
</script>
<script src="{{ asset('js/componentes/filtros-inventario.js') }}"></script>
@endsection