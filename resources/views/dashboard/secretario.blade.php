@extends('layouts.app') 

@section('content')
@section('mostrarRegresar', 'false')

{{--- 1. TARJETA DE BIENVENIDA (Adaptada al Rol de Secretaría) ---}}
@include('components.tarjetas.tarjeta-bienvenido', [
    'titulo' => 'Bienvenido Panel de Gestión',   
    'descripcion' => 'Revisa, aprueba o rechaza las solicitudes de reserva de aulas y activos de la institución.'
])

{{--- 2. KPIs ENFOCADAS EN EL ESTADO DE LAS RESERVAS ---}}
<div class="contenedor-kpis">
    @component('components.filtros.kpi-selector', [
        'kpis' => [
            ['filtro' => 'pendientes', 'color' => 'azul',  'icono' => 'fas fa-clock',       'titulo' => 'Pendientes', 'subtitulo' => 'Por aprobar o rechazar'],
            ['filtro' => 'aprobadas',  'color' => 'verde', 'icono' => 'fas fa-check-circle', 'titulo' => 'Aprobadas',  'subtitulo' => 'Reservas aseguradas'],
            ['filtro' => 'rechazadas', 'color' => 'rojo',  'icono' => 'fas fa-times-circle', 'titulo' => 'Rechazadas', 'subtitulo' => 'Solicitudes denegadas']
        ]
    ])
    @endcomponent
</div>

{{--- 3. FILTRO RÁPIDO PARA LAS SOLICITUDES ---}}
<div class="filtro-rapido-contenedor">
    @include('components.filtros.filtro-rapido', ['opciones' => ['hoy', 'esta semana', 'historial']])
</div>

{{--- 4. ESPACIO ENFOCADO EN EL LISTADO DE RESERVAS ---}} 
<div class="container-solicitudes-reservas" style="margin-top: 30px;">
    <!-- Aquí renderizaremos las tarjetas o la tabla de reservas pendientes cuando entremos a ese módulo -->
    <div class="alerta-informativa" style="padding: 20px; background-color: var(--color-fondo-bloque, #f8f9fa); border-left: 4px solid var(--color-borde, #ccc); border-radius: 4px;">
        <p style="margin: 0; font-family: var(--fuente-principal); color: var(--color-texto-secundario); font-size: 0.95rem;">
            <i class="fas fa-calendar-alt" style="margin-right: 8px;"></i> **Módulo de Gestión de Reservas:** Espacio reservado para el control de solicitudes entrantes de los docentes.
        </p>
    </div>
</div>

@endsection