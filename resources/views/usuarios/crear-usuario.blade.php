{{-- resources/views/usuarios/crear-usuario.blade.php --}}

{{---SOLO ENTRA LA SECRETARIA--}}

@extends('layouts.app')

@section('mostrarBusqueda', 'false')
@section('mostrarRegresar', 'true')
@section('rutaRegresar', url('/usuarios')) {{-- Puedes cambiar esta ruta a donde quieras que regrese el botón --}}
@section('content')
    <link rel="stylesheet" href="{{ asset('css/pages/usuarios-crear.css') }}">

    <h2 class="titulo-pagina"><i class="fas fa-user-plus"></i> Crear Usuario</h2>

<div class="contenedor-registro-flexible">

    {{--BOTÓN DISPARADOR: Solo se ve en celulares/tablets (< 768px). Abre y cierra el formulario --}}
    <button class="btn-toggle-formulario" type="button" data-bs-toggle="collapse" data-bs-target="#formularioColapsable" aria-expanded="false" aria-controls="formularioColapsable">
        <span><i class="fas fa-user-plus"></i> Formulario de Registro</span>
        <i class="fas fa-chevron-down"></i>
    </button>

    {{--CONTENEDOR COLAPSABLE: En móviles arranca cerrado, en PC ignora el colapso y se queda abierto --}}
    <div class="collapse dont-collapse-md" id="formularioColapsable">
        <form class="form-registrar" action="POST" method="post">
            @csrf {{-- ¡No olvides el token de seguridad de Laravel! --}}

            <h3>Registrar nuevo usuario</h3>

            <div class="post-form">
                <label for="rol">Rol</label>
                <select name="rol" id="rol">
                    <option value="">--Docente--</option>
                </select>
            </div>

            <div class="post-form">
                <label for="name">Primer Nombre</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="post-form">
                <label for="second-name">Segundo Nombre</label>
                <input type="text" id="second-name" name="second-name">
            </div>

            <div class="post-form">
                <label for="lastname">Primer Apellido</label>
                <input type="text" id="lastname" name="lastname" required>
            </div>
          
            <div class="post-form">
                <label for="second-last-name">Segundo Apellido</label>
                <input type="text" id="second-last-name" name="second-last-name">
            </div>

            <div class="post-form">
                <label for="identificacion">Cédula</label>
                <input type="text" id="identificacion" name="identificacion">
            </div>

            <div class="post-form">
                <label for="correo">Correo</label>
                <input type="email" id="correo" name="correo"> {{-- Corregido 'mail' por 'email' --}}
            </div>

            <div class="contenedor-botones">
                <x-botones.boton class="btn">Registrar</x-botones.boton> 
                <x-botones.boton class="btn-rojo">Cancelar</x-botones.boton>  
            </div>   
        </form>
    </div>

    {{--TARJETA LATERAL: En móvil queda visible desde el inicio abajo del botón --}}
    <div class="tarjeta-lateral-gestion">
        
        {{-- BLOQUE 1: RESUMEN DE CUENTAS --}}
        <div class="bloque-estadisticas">
            <h3><i class="fas fa-chart-pie"></i> Estado del Sistema</h3>
            <p class="subtexto-tarjeta">Registro exclusivo para el rol de <strong>Secretario(a)</strong>.</p>
            
            <div class="grid-contadores">
                <div class="tarjeta-contador">
                    <span class="numero-contador">48</span>{{----/*cambiar a dinamico*/----}}
                    <span class="etiqueta-contador">Registrados</span>
                </div>
                <div class="tarjeta-contador">
                    <span class="numero-contador">12</span>{{---//cambiar a dinamico//---}}
                    <span class="etiqueta-contador">Activos este mes</span>
                </div>
            </div>
        </div>

        <hr class="divisor-tarjeta">

        {{-- BLOQUE 2: GUÍA DE REGISTRO SEGURO --}}
        <div class="bloque-guia-segura">
            <h3><i class="fas fa-shield-alt"></i> Guía de Registro Seguro</h3>
            
            <div class="alerta-informativa-azul">
                <p>Por favor, siga estas reglas para mantener la integridad de la base de datos:</p>
            </div>

            <ul class="lista-reglas-digitacion">
                <li>
                    <strong>Nombres completos:</strong> 
                    Escriba los nombres y apellidos tal como aparecen en el documento, usando mayúscula inicial (ej: <em>Juan Carlos</em>).
                </li>
                <li>
                    <strong>Documento de identidad:</strong> 
                    Digite únicamente los números. <u>No incluya</u> puntos, comas, guiones ni espacios.
                </li>
                <li>
                    <strong>Correo:</strong> 
                    Valide que el correo termine en un dominio oficial (ej: <em>@colegio.edu.co o @gmail.com</em>).
                </li>
                <li>
                    <strong>Seguridad inicial:</strong> 
                    La contraseña por defecto se asignará automáticamente igual al número de documento del usuario.
                </li>
            </ul>
        </div>

    </div>

</div>
@endsection