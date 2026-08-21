@props([
    'modo' => 'crear',
    'usuario' => null
])


@if('crear' === $modo || 'editar-admin' === $modo)
    {{-- ROL: Solo visible para crear o editar --}} 
    <div class="post-form">
        <label for="rol">Rol</label>
        <select name="rol" id="rol">
            <option value="">--Selecciona--</option>
            <option value="2" {{ old('rol', $usuario->rol ?? '') == '2' ? 'selected' : '' }}>Docente</option>
            <option value="3" {{ old('rol', $usuario->rol ?? '') == '3' ? 'selected' : '' }}>Rector(a)</option>
        </select>
    </div>
@endif

{{-- PRIMER NOMBRE --}}
<div class="post-form">
    <label for="name">Primer Nombre <span class="text-danger">*</span></label>
    <input type="text" id="name" name="name" required 
           value="{{ old('name', $usuario->name ?? '') }}">
</div>

{{-- SEGUNDO NOMBRE --}}
<div class="post-form">
    <label for="second-name">Segundo Nombre</label>
    <input type="text" id="second-name" name="second-name" 
           value="{{ old('second-name', $usuario->second_name ?? '') }}">
</div>

{{-- PRIMER APELLIDO --}}
<div class="post-form">
    <label for="lastname">Primer Apellido <span class="text-danger">*</span></label>
    <input type="text" id="lastname" name="lastname" required 
           value="{{ old('lastname', $usuario->lastname ?? '') }}">
</div>

{{-- SEGUNDO APELLIDO --}}
<div class="post-form">
    <label for="second-last-name">Segundo Apellido</label>
    <input type="text" id="second-last-name" name="second-last-name" 
           value="{{ old('second-last-name', $usuario->second_last_name ?? '') }}">
</div>

@if('crear' === $modo || 'editar-admin' === $modo)
    {{-- ESTADO: Solo visible para crear o editar --}}
    <div class="post-form">
        <label for="estado">Estado</label>
        <select name="estado" id="estado">
            <option value="">--Selecciona--</option>
            <option value="Activo" {{ old('estado', $usuario->estado ?? '') == 'Activo' ? 'selected' : '' }}>Activo</option>
            <option value="Inactivo" {{ old('estado', $usuario->estado ?? '') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
        </select>
    </div>
@endif

@if('crear' === $modo || 'editar-admin' === $modo)
    {{-- CÉDULA --}}
    <div class="post-form">
        <label for="identificacion">Cédula <span class="text-danger">*</span></label>
        <input type="text" id="identificacion" name="identificacion" 
               value="{{ old('identificacion', $usuario->identificacion ?? '') }}"
               {{ isset($usuario->id) ? 'readonly' : '' }}>
    </div>
@endif


{{-- CORREO --}}
<div class="post-form">
    <label for="correo">Correo <span class="text-danger">*</span></label>
    <input type="email" id="correo" name="correo" 
           value="{{ old('correo', $usuario->correo ?? '') }}">
</div>

{{-- CONDICIONAL CONTRASEÑA: Solo si se está CREANDO un usuario --}}
@if('crear' === $modo || 'editar-admin' === $modo)
    <div class="post-form">
        <label for="password">Contraseña Inicial <span class="text-danger">*</span></label>
        <input type="password" id="password" name="password" required 
               autocomplete="new-password" placeholder="Asigna una clave temporal">
    </div>
@endif

<div class="contenedor-botones">
    <x-botones.boton 
        class="btn btn-rojo" 
        type="button" 
        onclick="ejecutarCierreUniversal(this)">
        Cancelar
    </x-botones.boton>

    <x-botones.boton class="btn btn-verde" type="submit">
        @if('perfil' === $modo || 'editar-admin' === $modo  || 'editar' === $modo)
            Guardar Cambios {{-- Si la URL es de perfil o editar, muestra esto --}}
        @else
            Registrar {{-- Para cualquier otra vista (Crear), muestra esto --}}
        @endif
    </x-botones.boton>
</div>

<script>
function ejecutarCierreUniversal(boton) {
    // 1. Siempre limpiamos los inputs primero
    let formulario = boton.closest('form');
    if (formulario) formulario.reset();

    // 2. Buscamos el contenedor padre (sirve para ambos diseños)
    let contenedor = boton.closest('.collapse') 
                  || boton.closest('#contenedor-formulario') 
                  || boton.closest('.formulario-desplegable');
    
    if (contenedor) {
        // Quitamos la clase del perfil personalizado
        contenedor.classList.remove('activo');
        
        // Quitamos las clases de Bootstrap por si acaso
        contenedor.classList.remove('show');
        
        // Cierre nativo mediante la API de Bootstrap si está presente
        if (window.bootstrap && bootstrap.Collapse) {
            let bsCollapse = bootstrap.Collapse.getInstance(contenedor);
            if (bsCollapse) {
                bsCollapse.hide();
            }
        }
        
        // Si estamos en móvil, aseguramos el comportamiento visual directo
        if (window.innerWidth <= 768) {
            contenedor.style.display = 'none';
        }
    }
}

if(modo=== 'perfil'){
    // Deshabilitar campos de edición en modo perfil
    let inputRol = document.getElementById('rol');
    let inputEstado = document.getElementById('estado');
    let inputIdentificacion = document.getElementById('identificacion');
    inputRol.disabled = true;
    inputEstado.disabled = true;
    inputIdentificacion.disabled = true;
}

//Al hacer sumit del formulario, deshabilitar el botón para evitar múltiples envíos
let form = document.querySelector('form');
if(form){
    form.addEventListener('submit', function() {
        let submitButton = form.querySelector('button[type="submit"]');
        if(submitButton){
            submitButton.disabled = true;
            submitButton.innerHTML = 'Procesando...';
        }
    });
}

// Función para manejar el envío del formulario según el modo
function handlerSubmit() {
    // Function implementation
    if(modo === 'crear' ) {
       api.crearUsuarios(
            document.getElementById('name').value,
            document.getElementById('second-name').value,
            document.getElementById('lastname').value,
            document.getElementById('second-last-name').value,
            document.getElementById('identificacion').value,
            document.getElementById('correo').value,
            document.getElementById('rol').value,
            document.getElementById('estado').value,
            document.getElementById('password').value
        );
    }else if(modo === 'editar' || modo === 'editar-admin') {
        api.editarUsuarios(
            document.getElementById('name').value,
            document.getElementById('second-name').value,
            document.getElementById('lastname').value,
            document.getElementById('second-last-name').value,
            document.getElementById('identificacion').value,
            document.getElementById('correo').value,
            document.getElementById('rol') ? document.getElementById('rol').value : null,
            document.getElementById('estado') ? document.getElementById('estado').value : null
        );
    }
}
</script>