@extends('layouts.app')

@section('mostrarBusqueda', 'true')
@section('mostrarRegresar', 'true')
@section('rutaRegresar', url('/perfil/secretario'))
@section('mostrarPerfil', 'false')

@section('content')
<link rel="stylesheet" href="{{ asset('css/pages/perfil.css') }}">

<div class="siger-modulo-perfil" style="padding: 2rem;">
    
    {{-- ENCABEZADO PRINCIPAL DE LA VISTA --}}
    <div class="perfil-header-seccion" style="margin-bottom: 2rem;">
        <h2 class="perfil-titulo-principal">Gestión de Usuarios</h2>
        <p class="perfil-subtitulo">Supervisa, activa, desactiva o elimina las cuentas del personal de la institución.</p>
    </div>

    {{-- CONTENEDOR BLANCO CON PESTAÑAS Y TABLAS --}}
    <div class="tarjeta-blanca-datos" style="padding: 1.5rem; overflow-x: auto;">
        
        {{-- 1. BOTONES DE NAVEGACIÓN PARA CAMBIAR PESTAÑAS (TABS) --}}
        <div class="tabs-gestion-admin" style="display: flex; gap: 1rem; border-bottom: 2px solid #e2e8f0; margin-bottom: 1.5rem;">
            {{-- Pestaña para Activos (Inicia visible por defecto) --}}
            <button class="tab-btn activo" data-tab="tab-activos" style="padding: 1rem; border: none; background: none; font-weight: bold; cursor: pointer; border-bottom: 3px solid var(--color-principal); color: var(--color-texto);">
                Usuarios Activos
            </button>
            {{-- Pestaña para Inactivos --}}
            <button class="tab-btn" data-tab="tab-inactivos" style="padding: 1rem; border: none; background: none; font-weight: bold; cursor: pointer; color: var(--color-principal);">
                Usuarios Inactivos
            </button>
        </div>

        {{-- 2. CONTENIDO PESTAÑA 1: USUARIOS ACTIVOS --}}
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
                    {{-- Filtramos la colección general de usuarios traída del controlador --}}
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
                                    {{ $usuario->role->ROL_NOMBRE ?? $usuario->role->ROL_ID ?? 'Sin Rol' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-status activo">Activo</span>
                            </td>
                            <td style="text-align: center;">
                                <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                    {{-- Botón para ir al formulario de edición del usuario --}}
                                    <x-botones.boton 
                                        type="button" 
                                        class="btn-siger-accion btn-azul" 
                                        style="padding: 6px 16px; font-size: 0.85rem; width: auto;"
                                        onclick="window.location.href=">
                                        Editar
                                    </x-botones.boton>

                                    {{-- Botón para abrir el modal: 
                                         - data-bs-toggle y data-bs-target le dicen a Bootstrap qué modal abrir.
                                         - onclick ejecuta nuestra función JS inyectando los datos de ESTE usuario. --}}
                                    <x-botones.boton 
                                        type="button" 
                                        class="btn-siger-accion btn-rojo" 
                                        style="padding: 6px 16px; font-size: 0.85rem; width: auto;"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalGestionUsuario"
                                        onclick="prepararModal('{{ $usuario->user_id ?? $usuario->id }}', '{{ $usuario->USU_PRIMER_NOMBRE }} {{ $usuario->USU_PRIMER_APELLIDO }}', 'Activo')">
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

        {{-- 3. CONTENIDO PESTAÑA 2: USUARIOS INACTIVOS (Oculta por defecto con display: none) --}}
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
                    {{-- Filtramos los usuarios cuyo estado sea diferente de 'Activo' --}}
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
                                    {{ $usuario->role->ROL_NOMBRE ?? $usuario->role->ROL_ID ?? 'Sin Rol' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-status inactivo">Inactivo</span>
                            </td>
                            <td style="text-align: center;">
                                <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                    {{-- Botón de envío directo para Reactivar la cuenta --}}
                                    <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="USU_ESTADO" value="Activo">
                                        <x-botones.boton 
                                            type="submit" 
                                            class="btn-siger-accion btn-verde-siger" 
                                            style="padding: 6px 16px; font-size: 0.85rem; width: auto;">
                                            Activar
                                        </x-botones.boton>
                                    </form>

                                    {{-- Botón para abrir modal en modo 'Inactivo' --}}
                                    <x-botones.boton 
                                        type="button" 
                                        class="btn-siger-accion btn-rojo" 
                                        style="padding: 6px 16px; font-size: 0.85rem; width: auto;"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalGestionUsuario"
                                        onclick="prepararModal('{{ $usuario->user_id ?? $usuario->id }}', '{{ $usuario->USU_PRIMER_NOMBRE }} {{ $usuario->USU_PRIMER_APELLIDO }}', 'Inactivo')">
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

{{-- 4. INSERCIÓN DE TU COMPONENTE BLADE MODAL --}}
<x-modal id="modalGestionUsuario" titulo="Gestionar Usuario" subtitulo="Acción de cuenta">
    
    {{-- Párrafo explicativo dinámico (su texto cambia con JS) --}}
    <p id="modalMensajeTexto" style="color: var(--color-texto); margin-bottom: 1.5rem;">
        ¿Qué acción deseas realizar sobre este usuario?
    </p>

    <div style="display: flex; flex-direction: row; gap: 0.75rem; width: 100%; align-items: stretch;">
    
    {{-- Formulario 1: Desactivar --}}
    <form id="formCambiarEstadoModal" method="POST" action="" style="flex: 1; margin: 0; display: flex;">
        @csrf
        @method('PUT')
        <input type="hidden" name="USU_ESTADO" id="inputEstadoModal" value="Inactivo">
        <x-botones.boton type="submit" id="btnDesactivarModal" class="btn-siger-accion btn-azul" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
            Desactivar Cuenta
        </x-botones.boton>
    </form>

    {{-- Formulario 2: Eliminar --}}
    <form id="formEliminarModal" method="POST" action="" style="flex: 1; margin: 0; display: flex;">
        @csrf
        @method('DELETE')
        <x-botones.boton type="submit" class="btn-siger-accion btn-rojo" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;" onclick="return confirm('¿Confirmas que deseas ELIMINAR permanentemente este usuario? Esta acción no se puede deshacer.');">
            Eliminar Definitivamente
        </x-botones.boton>
    </form>

</div>
</x-modal>

{{-- 5. SCRIPT DE LÓGICA FRONTEND --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // ==========================================
        // LÓGICA DE INTERCAMBIO DE PESTAÑAS (TABS)
        // ==========================================
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContenidos = document.querySelectorAll('.tab-contenido');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Desactivar y quitar estilos de todos los botones de pestaña
                tabBtns.forEach(b => {
                    b.classList.remove('activo');
                    b.style.borderBottom = 'none';
                    b.style.color = 'var(--color-azulado)';
                });

                // Ocultar todos los contenidos de las pestañas
                tabContenidos.forEach(c => c.style.display = 'none');

                // Activar visualmente el botón sobre el que se hizo clic
                this.classList.add('activo');
                this.style.borderBottom = '3px solid var(--color-principal)';
                this.style.color = 'var(--color-texto)';

                // Mostrar la sección correspondiente según el atributo 'data-tab'
                const targetTab = this.getAttribute('data-tab');
                document.getElementById(targetTab).style.display = 'block';
            });
        });
    });

    // =======================================================
    // LÓGICA DE PREPARACIÓN DEL MODAL (ANTES DE ABRIRSE)
    // =======================================================
    // Esta función recibe el ID, Nombre y Estado del usuario sobre el que hiciste clic.
    function prepararModal(id, nombre, estadoActual) {
        
        // A. Obtenemos referencias a los elementos dentro del componente modal
        const tituloDinamico = document.getElementById('modal-titulo-dinamico');
        const subDinamico = document.getElementById('modal-sub-dinamico');
        const mensaje = document.getElementById('modalMensajeTexto');
        const formEstado = document.getElementById('formCambiarEstadoModal');
        const formEliminar = document.getElementById('formEliminarModal');

        // B. Inyectamos el nombre del usuario en los encabezaos dinámicos del modal
        if (tituloDinamico) tituloDinamico.innerText = `Gestionar: ${nombre}`;
        if (subDinamico) subDinamico.innerText = `Estado actual: ${estadoActual}`;

        // C. Actualizamos las URLs 'action' de los formularios para que apunten al ID correcto
        formEstado.action = `/usuarios/${id}`;
        formEliminar.action = `/usuarios/${id}`;

        // D. Adaptamos el contenido dependiendo de si el usuario está Activo o Inactivo
        if (estadoActual === 'Activo') {
            mensaje.innerText = `Selecciona la acción para ${nombre}. Puedes desactivar temporalmente su acceso o eliminar la cuenta definitivamente.`;
            formEstado.style.display = 'block'; // Mostramos el botón de Desactivar
        } else {
            mensaje.innerText = `El usuario ${nombre} está inactivo. Puedes proceder con la eliminación definitiva de su cuenta.`;
            formEstado.style.display = 'none';  // Ocultamos 'Desactivar' pues ya está inactivo
        }
    }
</script>
@endsection