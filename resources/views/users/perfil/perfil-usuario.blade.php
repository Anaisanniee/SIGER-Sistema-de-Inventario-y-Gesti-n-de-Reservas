@extends('layouts.app')

@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'true')
@section('mostrarPerfil', 'false')

@section('content')

{{-- VARIABLE TEMPORAL DE PRUEBA: Cambia a 'rector' o 'docente' para verificar la vista --}}
@php $rolPrueba = 'rector'; @endphp

<link rel="stylesheet" href="{{ asset('css/pages/perfil.css') }}">
{{-- Estilos necesarios para la maquetación de las tarjetas de reservas --}}
<link rel="stylesheet" href="{{ asset('css/pages/reservas.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/tarjeta-reserva.css') }}">

<div class="siger-modulo-perfil">
    
    {{-- BARRA SUPERIOR DE CONTEXTO --}}
    <div class="perfil-header-seccion">
        <h2 class="perfil-titulo-principal">Mi perfil</h2>
        <p class="perfil-subtitulo">Gestiona tu cuenta e información personal en SIGER</p>
    </div>

    <div class="perfil-layout-contenedor">
        
        {{-- COLUMNA IZQUIERDA: AVATAR, DATOS Y ACCIONES --}}
        <aside class="perfil-columna-izquierda">
            <div class="avatar-wrapper">
                <div class="avatar-circulo">
                    @auth
                        {{ strtoupper(substr(Auth::user()->name, 0, 1) . substr(Auth::user()->lastname ?? 'U', 0, 1)) }}
                    @else
                        US
                    @endauth
                </div>
            </div>
            
            <h3 class="docente-nombre">{{ Auth::user()->name ?? 'Usuario' }} {{ Auth::user()->lastname ?? '' }}</h3>
            <p class="docente-email">{{ Auth::user()->email ?? 'correo@ejemplo.com' }}</p>

            <div class="informacion-lateral-lista">
                <div class="item-lateral">
                    <span class="item-titulo">Estado</span>
                    <span class="item-valor">Activo</span>
                </div>
                <div class="item-lateral">
                    <span class="item-titulo">Rol</span>
                    <span class="item-valor" style="text-transform: capitalize;">{{ $rolPrueba ?? Auth::user()->rol ?? 'Docente' }}</span>
                </div>
                <div class="item-lateral">
                    <span class="item-titulo">Reservas activas</span>
                    <span class="item-valor badge-reserva">{{ count($misReservas ?? [1, 2]) }}</span>
                </div>
            </div>

            <div class="acciones-laterales">
                {{-- Botón Editar Perfil --}}
                <x-botones.boton id="btn-editar-perfil" type="button" class="btn-siger-accion btn-verde-siger">
                    Editar Perfil
                </x-botones.boton>

                {{-- 
                    ======================================================================
                    SECCIÓN EXCLUSIVA PARA EL RECTOR:
                    Redirección condicional cuando el usuario con rol rector visualiza el perfil.
                    ======================================================================
                --}}
                @if(($rolPrueba ?? Auth::user()->rol ?? '') === 'rector')
                    <a href="" style="text-decoration: none; width: 100%;">
                        <x-botones.boton type="button" class="btn-siger-accion btn-azul">
                            <i class="fas fa-file-alt"></i> Ver Informes de Inventario
                        </x-botones.boton>
                    </a>
                @endif

                {{-- Botón Logout compacto --}}
                <x-botones.boton-logout />
            </div>
        </aside>

        {{-- COLUMNA DERECHA --}}
        <main class="perfil-columna-derecha">
            
            {{-- SECCIÓN 1: FORMULARIO DE EDICIÓN --}}
            <div class="formulario-desplegable" id="contenedor-formulario">
                <div class="tarjeta-blanca-datos">
                    <div class="titulo-ficha-datos">
                        <span>Información personal</span>
                    </div>

                    @include('components.formularios.form-usuario', ['usuario' => $usuario ?? null, 'modo' => 'perfil'])
                </div>
            </div>

            {{-- 
                ======================================================================
                SECCIÓN 2: MIS RESERVAS UTILIZANDO EL CONTENEDOR VERTICAL Y @component
                ======================================================================
            --}}
            <div class="tarjeta-blanca-datos" id="contenedor-reservas">
                <div class="titulo-ficha-datos">
                    <span>Mis Reservas</span>
                </div>

                {{-- Se añade la clase container-tarjetas-vertical para conservar los estilos correctos --}}
                <div class="container-tarjetas-vertical" style="margin-top: 1.5rem;">
                   {{-- Se utiliza @forelse para manejar el caso de no tener reservas SON DATOS DE PRUEBA --}}
                @forelse($misReservas ?? [
                        (object)[
                            'id' => 101,
                            'recurso_nombre' => 'Aula 101 - Sistemas',
                            'estado' => 'pendiente',
                            'usuario_nombre' => Auth::user()->name ?? 'Docente',
                            'fecha_inicio' => '2026-08-30',
                            'hora_inicio' => '08:00 AM',
                            'hora_fin' => '10:00 AM',
                            'ubicacion' => 'Bloque A - Piso 1',
                            'es_multiple' => false,
                            'recursos_lista' => []
                        ],
                        (object)[
                            'id' => 102,
                            'recurso_nombre' => 'Proyector Epson X500',
                            'estado' => 'aprobada',
                            'usuario_nombre' => Auth::user()->name ?? 'Docente',
                            'fecha_inicio' => '2026-09-01',
                            'hora_inicio' => '10:00 AM',
                            'hora_fin' => '12:00 PM',
                            'ubicacion' => 'Sala de Profesores',
                            'es_multiple' => false,
                            'recursos_lista' => []
                        ]
                    ] as $reserva)

                        {{-- Wrapper individual con tag data para consistencia de maquetación --}}
                        <div class="tarjeta-wrapper recurso-item">
                            @component('components.tarjetas.tarjeta-reserva', [
                                'id'          => $reserva->id,
                                'foto'        => asset('storage/images/activos/default.jpeg'),
                                'nombre'      => $reserva->recurso_nombre,
                                'estado'      => $reserva->estado,
                                'solicitante' => $reserva->usuario_nombre,
                                'fecha'       => \Carbon\Carbon::parse($reserva->fecha_inicio)->format('d/m/Y'),
                                'horaInicio'  => $reserva->hora_inicio,
                                'horaFin'     => $reserva->hora_fin,
                                'ubicacion'   => $reserva->ubicacion,
                                'urlGestion'  => '#',
                                'esMultiple'  => $reserva->es_multiple ?? false,
                                'recursos'    => $reserva->recursos_lista ?? []
                            ])
                            @endcomponent
                        </div>

                    @empty
                        <div class="modulo-placeholder text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h4>Aún no tienes reservas</h4>
                            <p class="text-muted">Cuando realices reservas, aparecerán aquí para que puedas gestionarlas fácilmente.</p>
                        </div>
                    @endforelse
                </div>
            </div>
          
        </main>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnEditar = document.getElementById('btn-editar-perfil');
        const btnCancelar = document.getElementById('btn-perfil-cancelar');
        const contenedorForm = document.getElementById('contenedor-formulario');

        if(btnEditar && contenedorForm) {
            btnEditar.addEventListener('click', function() {
                contenedorForm.classList.toggle('activo');
            });
        }

        if(btnCancelar && contenedorForm) {
            btnCancelar.addEventListener('click', function(e) {
                e.preventDefault();
                contenedorForm.classList.remove('activo');
            });
        }
    });
</script>

@endsection