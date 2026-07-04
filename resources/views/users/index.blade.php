@extends('layouts.app')

@section('mostrarBusqueda', 'true')
@section('mostrarRegresar', 'true')
@section('rutaRegresar', url('/perfil/secretario')) {{-- O la ruta de retorno segura --}}
@section('mostrarPerfil', 'false')
@section('content')
<link rel="stylesheet" href="{{ asset('css/pages/perfil.css') }}">

<div class="siger-modulo-perfil" style="padding: 2rem;">
    
    {{-- ENCABEZADO DE LA SECCIÓN --}}
    <div class="perfil-header-seccion" style="margin-bottom: 2rem;">
        <h2 class="perfil-titulo-principal">Gestión de Usuarios</h2>
        <p class="perfil-subtitulo">Activa, desactiva y supervisa las cuentas del personal de la institución.</p>
    </div>

    {{-- TARJETA BLANCA CONTENEDORA DE LA TABLA --}}
    <div class="tarjeta-blanca-datos" style="padding: 1.5rem; overflow-x: auto;">
        
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
                
                {{-- ITERACIÓN DINÁMICA CON LOS DATOS REALES DE TU CONTROLADOR --}}
                @forelse($users as $usuario)
                    <tr>
                        <td>
                            <div class="user-info-cell">
                                <span class="user-name">
                                    {{ $usuario->USU_PRIMER_NOMBRE }} 
                                    {{ $usuario->USU_SEGUNDO_NOMBRE }} 
                                    {{ $usuario->USU_PRIMER_APELLIDO }} 
                                    {{ $usuario->USU_SEGUNDO_APELLIDO }}
                                </span>
                                <span class="user-email">{{ $usuario->USU_CORREO }}</span>
                            </div>
                        </td>
                        <td>{{ $usuario->USU_CEDULA }}</td>
                        <td>
                            <span style="text-transform: capitalize;">
                                {{ $usuario->role->ROL_ID ?? 'Sin Rol' }} {{-- Cambia 'nombre' por la columna real de la tabla roles --}}
                            </span>
                        </td>
                        <td>
                            @if($usuario->USU_ESTADO === 'Activo')
                                <span class="badge-status activo">Activo</span>
                            @else
                                <span class="badge-status inactivo">Inactivo</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            {{-- Formulario dinámico simulado para cambiar el estado o eliminar --}}
                            <form action="#" method="POST" class="form-cambiar-estado" 
                                  data-usuario="{{ $usuario->USU_PRIMER_NOMBRE }} {{ $usuario->USU_PRIMER_APELLIDO }}" 
                                  data-accion="{{ $usuario->USU_ESTADO === 'Activo' ? 'desactivar' : 'activar' }}">
                                @csrf
                                <x-botones.boton 
                                    style="padding: 6px 16px; font-size: 0.85rem; width: auto;"
                                    type="button" 
                                    class="btn-siger-accion btn-azul" 
                                    onclick="window.location.href='{{ route('usuarios.edit', $usuario->user_id) }}'">
                                    Editar
                                </x-botones.boton>

                                @if($usuario->USU_ESTADO === 'Activo')
                                    <x-botones.boton type="submit" class="btn-siger-accion btn-rojo" style="padding: 6px 16px; font-size: 0.85rem; width: auto;">
                                        Desactivar
                                    </x-botones.boton>
                                @else
                                    <x-botones.boton type="submit" class="btn-siger-accion btn-verde-siger" style="padding: 6px 16px; font-size: 0.85rem; width: auto;">
                                        Activar
                                    </x-botones.boton>
                                @endif
                            </form>
                        </td>
                    </tr>
                @empty
                    {{-- Por si la base de datos llega a estar vacía --}}
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--color-principal); padding: 2rem;">
                            No se encontraron usuarios registrados en el sistema.
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>

    </div>
</div>

{{-- SCRIPT DE INTERACTIVIDAD PARA LAS CONFIRMACIONES EN EL FRONTEND --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formularios = document.querySelectorAll('.form-cambiar-estado');
        
        formularios.forEach(form => {
            form.addEventListener('submit', function(e) {
                const nombre = this.getAttribute('data-usuario');
                const accion = this.getAttribute('data-accion');
                
                const confirmar = confirm(`¿Estás segura de que deseas ${accion} al usuario "${nombre}" en el sistema SIGER?`);
                
                if (!confirmar) {
                    e.preventDefault(); // Evita que se recargue o envíe la acción si se cancela
                }
            });
        });
    });
</script>
@endsection