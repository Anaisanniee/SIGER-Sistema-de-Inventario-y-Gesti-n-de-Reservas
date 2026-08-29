@extends('layouts.app')

@section('mostrarBusqueda', 'true')
@section('mostrarRegresar', 'true')
@section('rutaRegresar', route('perfil'))
@section('mostrarPerfil', 'false')

@section('content')
<link rel="stylesheet" href="{{ asset('css/pages/perfil.css') }}">

<div class="siger-modulo-perfil" style="padding: 2rem;">
    
    {{-- ENCABEZADO PRINCIPAL CON BOTÓN DE CREAR USUARIO --}}
    <div class="perfil-header-seccion" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 class="perfil-titulo-principal" style="color: var(--color-principal);">Gestión de Usuarios</h2>
            <p class="perfil-subtitulo">Supervisa, activa, desactiva o elimina las cuentas del personal de la institución.</p>
        </div>

        {{-- Botón de acceso directo a Registro --}}
        <a href="{{ route('usuarios.create') }}" style="text-decoration: none;">
            <x-botones.boton type="button" class="btn btn-siger-accion btn-verde-siger" style="width: auto; padding: 10px 20px;">
                <i class="fas fa-user-plus" style="margin-right: 8px;"></i> Crear Nuevo Usuario
            </x-botones.boton>
        </a>
    </div>

    {{-- CONTENEDOR BLANCO CON PESTAÑAS Y TABLAS --}}
    <div class="tarjeta-blanca-datos" style="padding: 1.5rem; overflow-x: auto;">
        
        {{-- 1. PESTAÑAS (TABS) --}}
        <div class="tabs-gestion-admin" style="display: flex; gap: 1rem; border-bottom: 2px solid #e2e8f0; margin-bottom: 1.5rem;">
            <button class="tab-btn activo" data-tab="tab-activos" style="padding: 1rem; border: none; background: none; font-weight: bold; cursor: pointer; border-bottom: 3px solid var(--color-principal); color: var(--color-texto);">
                Usuarios Activos
            </button>
            <button class="tab-btn" data-tab="tab-inactivos" style="padding: 1rem; border: none; background: none; font-weight: bold; cursor: pointer; color: var(--color-texto);">
                Usuarios Inactivos
            </button>
        </div>

        {{-- 2. PESTAÑA 1: USUARIOS ACTIVOS --}}
        <div class="tab-contenido" id="tab-activos">
            <table class="siger-tabla">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Identificación</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php $activos = $users->where('USU_ESTADO', 'Activo'); @endphp

                    @forelse($activos as $usuario)
                        <tr>
                            <td>
                                <div class="user-info-cell">
                                    <span class="user-name">
                                        {{ $usuario->USU_PRIMER_NOMBRE }} {{ $usuario->USU_SEGUNDO_NOMBRE }} 
                                        {{ $usuario->USU_PRIMER_APELLIDO }} {{ $usuario->USU_SEGUNDO_APELLIDO }}
                                    </span>
                                    <span class="user-email">{{ $usuario->USU_CORREO }}</span>
                                </div>
                            </td>
                            <td>{{ $usuario->USU_CEDULA }}</td>
                            <td>
                                <span style="text-transform: capitalize;">
                                    {{ $usuario->role->ROL_NOMBRE ?? $usuario->role->name ?? 'Sin Rol' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-status activo">Activo</span>
                            </td>
                            <td style="text-align: center;">
                                <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                    {{-- Botón Editar --}}
                                    <x-botones.boton 
                                        type="button" 
                                        class="btn-siger-accion btn btn-azul" 
                                        style="padding: 6px 16px; font-size: 0.85rem; width: auto;"
                                        onclick="window.location.href='{{ route('usuarios.edit', $usuario->USU_ID) }}'">
                                        Editar
                                    </x-botones.boton>

                                    {{-- Botón Gestionar Modal --}}
                                    <x-botones.boton 
                                        type="button" 
                                        class="btn-siger-accion btn btn-rojo" 
                                        style="padding: 6px 16px; font-size: 0.85rem; width: auto;"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalGestionUsuario"
                                        onclick="prepararModal('{{ $usuario->USU_ID }}', '{{ $usuario->USU_PRIMER_NOMBRE }} {{ $usuario->USU_PRIMER_APELLIDO }}', 'Activo')">
                                        Gestionar
                                    </x-botones.boton>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--color-principal); padding: 2rem;">
                                No hay usuarios activos registrados actualmente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- 3. PESTAÑA 2: USUARIOS INACTIVOS --}}
        <div class="tab-contenido" id="tab-inactivos" style="display: none;">
            <table class="siger-tabla">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Identificación</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php $inactivos = $users->where('USU_ESTADO', '!=', 'Activo'); @endphp

                    @forelse($inactivos as $usuario)
                        <tr>
                            <td>
                                <div class="user-info-cell">
                                    <span class="user-name">
                                        {{ $usuario->USU_PRIMER_NOMBRE }} {{ $usuario->USU_SEGUNDO_NOMBRE }} 
                                        {{ $usuario->USU_PRIMER_APELLIDO }} {{ $usuario->USU_SEGUNDO_APELLIDO }}
                                    </span>
                                    <span class="user-email">{{ $usuario->USU_CORREO }}</span>
                                </div>
                            </td>
                            <td>{{ $usuario->USU_CEDULA }}</td>
                            <td>
                                <span style="text-transform: capitalize;">
                                    {{ $usuario->role->ROL_NOMBRE ?? $usuario->role->name ?? 'Sin Rol' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-status inactivo">Inactivo</span>
                            </td>
                            <td style="text-align: center;">
                                <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                    {{-- Botón Activar Directo --}}
                                    <form action="{{ route('usuarios.baja', $usuario->USU_ID) }}" method="POST" style="display: inline-flex; margin: 0;">
                                        @csrf
                                        @method('PATCH')
                                        <x-botones.boton
                                            type="submit"
                                            class="btn-siger-accion btn btn-verde-siger"
                                            style="padding: 6px 16px; font-size: 0.85rem; width: auto;">
                                            Activar
                                        </x-botones.boton>
                                    </form>

                                    {{-- Botón Gestionar Modal --}}
                                    <x-botones.boton 
                                        type="button" 
                                        class="btn-siger-accion btn btn-rojo" 
                                        style="padding: 6px 16px; font-size: 0.85rem; width: auto;"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalGestionUsuario"
                                        onclick="prepararModal('{{ $usuario->USU_ID }}', '{{ $usuario->USU_PRIMER_NOMBRE }} {{ $usuario->USU_PRIMER_APELLIDO }}', 'Inactivo')">
                                        Eliminar
                                    </x-botones.boton>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--color-principal); padding: 2rem;">
                                No hay usuarios inactivos en el sistema.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- COMPONENTE MODAL DE GESTIÓN --}}
<x-modal id="modalGestionUsuario" titulo="Gestionar Usuario" subtitulo="Acción de cuenta">
    <p id="modalMensajeTexto" style="color: var(--color-texto); margin-bottom: 1.5rem;">
        ¿Qué acción deseas realizar sobre este usuario?
    </p>

    <div style="display: flex; flex-direction: row; gap: 0.75rem; width: 100%; align-items: stretch;">
        
        {{-- Formulario Desactivar --}}
        <form id="formCambiarEstadoModal" method="POST" action="" style="flex: 1; margin: 0; display: flex;">
            @csrf
            @method('PATCH')
            <x-botones.boton type="submit" id="btnDesactivarModal" class="btn-siger-accion btn-azul" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                Desactivar Cuenta
            </x-botones.boton>
        </form>

        {{-- Formulario Eliminar --}}
        <form id="formEliminarModal" method="POST" action="" style="flex: 1; margin: 0; display: flex;">
            @csrf
            @method('DELETE')
            <x-botones.boton type="submit" class="btn-siger-accion btn-rojo" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;" onclick="return confirm('¿Confirmas que deseas ELIMINAR permanentemente este usuario? Esta acción no se puede deshacer.');">
                Eliminar Definitivamente
            </x-botones.boton>
        </form>

    </div>
</x-modal>

{{-- SCRIPT INTERACTIVO --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContenidos = document.querySelectorAll('.tab-contenido');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                tabBtns.forEach(b => {
                    b.classList.remove('activo');
                    b.style.borderBottom = 'none';
                    b.style.color = 'var(--color-azulado)';
                });

                tabContenidos.forEach(c => c.style.display = 'none');

                this.classList.add('activo');
                this.style.borderBottom = '3px solid var(--color-principal)';
                this.style.color = 'var(--color-texto)';

                const targetTab = this.getAttribute('data-tab');
                document.getElementById(targetTab).style.display = 'block';
            });
        });
    });

    function prepararModal(id, nombre, estadoActual) {
        const tituloDinamico = document.getElementById('modal-titulo-dinamico');
        const subDinamico = document.getElementById('modal-sub-dinamico');
        const mensaje = document.getElementById('modalMensajeTexto');
        const formEstado = document.getElementById('formCambiarEstadoModal');
        const formEliminar = document.getElementById('formEliminarModal');

        if (tituloDinamico) tituloDinamico.innerText = `Gestionar: ${nombre}`;
        if (subDinamico) subDinamico.innerText = `Estado actual: ${estadoActual}`;

        formEstado.action = `/usuarios/${id}/dar-de-baja`;
        formEliminar.action = `/usuarios/${id}`;

        if (estadoActual === 'Activo') {
            mensaje.innerText = `Selecciona la acción para ${nombre}. Puedes desactivar temporalmente su acceso o eliminar la cuenta definitivamente.`;
            if (formEstado) formEstado.style.display = 'block';
        } else {
            mensaje.innerText = `El usuario ${nombre} está inactivo. Puedes proceder con la eliminación definitiva de su cuenta.`;
            if (formEstado) formEstado.style.display = 'none';
        }
    }
</script>
@endsection