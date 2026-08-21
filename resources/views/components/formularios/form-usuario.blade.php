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
            <option value="2" {{ old('rol', $usuario->ROL_ID ?? '') == '2' ? 'selected' : '' }}>Docente</option>
            <option value="3" {{ old('rol', $usuario->ROL_ID ?? '') == '3' ? 'selected' : '' }}>Rector(a)</option>
        </select>
    </div>
@endif

{{-- PRIMER NOMBRE --}}
<div class="post-form">
    <label for="name">Primer Nombre <span class="text-danger">*</span></label>
    <input type="text" id="name" name="name" required 
           value="{{ old('name', $usuario->USU_PRIMER_NOMBRE ?? '') }}">
</div>

{{-- SEGUNDO NOMBRE --}}
<div class="post-form">
    <label for="second-name">Segundo Nombre</label>
    <input type="text" id="second-name" name="second-name" 
           value="{{ old('second-name', $usuario->USU_SEGUNDO_NOMBRE ?? '') }}">
</div>

{{-- PRIMER APELLIDO --}}
<div class="post-form">
    <label for="lastname">Primer Apellido <span class="text-danger">*</span></label>
    <input type="text" id="lastname" name="lastname" required 
           value="{{ old('lastname', $usuario->USU_PRIMER_APELLIDO ?? '') }}">
</div>

{{-- SEGUNDO APELLIDO --}}
<div class="post-form">
    <label for="second-last-name">Segundo Apellido</label>
    <input type="text" id="second-last-name" name="second-last-name" 
           value="{{ old('second-last-name', $usuario->USU_SEGUNDO_APELLIDO ?? '') }}">
</div>

@if('crear' === $modo || 'editar-admin' === $modo)
    {{-- ESTADO: Solo visible para crear o editar --}}
    <div class="post-form">
        <label for="estado">Estado</label>
        <select name="estado" id="estado">
            <option value="">--Selecciona--</option>
            <option value="Activo" {{ old('estado', $usuario->USU_ESTADO ?? '') == 'Activo' ? 'selected' : '' }}>Activo</option>
            <option value="Inactivo" {{ old('estado', $usuario->USU_ESTADO ?? '') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
        </select>
    </div>
@endif

@if('crear' === $modo || 'editar-admin' === $modo)
    {{-- CÉDULA --}}
    <div class="post-form">
        <label for="identificacion">Cédula <span class="text-danger">*</span></label>
        <input type="text" id="identificacion" name="identificacion" 
               value="{{ old('identificacion', $usuario->USU_CEDULA ?? '') }}"
               {{ isset($usuario->USU_ID) ? 'readonly' : '' }}>
    </div>
@endif

{{-- CORREO --}}
<div class="post-form">
    <label for="correo">Correo <span class="text-danger">*</span></label>
    <input type="email" id="correo" name="correo" required
           value="{{ old('correo', $usuario->USU_CORREO ?? '') }}">
</div>

{{-- CONDICIONAL CONTRASEÑA: Solo si se está CREANDO un usuario --}}
@if('crear' === $modo)
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
        @if('perfil' === $modo || 'editar-admin' === $modo || 'editar' === $modo)
            Guardar Cambios
        @else
            Registrar
        @endif
    </x-botones.boton>
</div>

<script>
function ejecutarCierreUniversal(boton) {
    let formulario = boton.closest('form');
    if (formulario) formulario.reset();

    let contenedor = boton.closest('.collapse') 
                  || boton.closest('#contenedor-formulario') 
                  || boton.closest('.formulario-desplegable');
    
    if (contenedor) {
        contenedor.classList.remove('activo');
        contenedor.classList.remove('show');
        
        if (window.bootstrap && bootstrap.Collapse) {
            let bsCollapse = bootstrap.Collapse.getInstance(contenedor);
            if (bsCollapse) {
                bsCollapse.hide();
            }
        }
        
        if (window.innerWidth <= 768) {
            contenedor.style.display = 'none';
        }
    }
}

// Al hacer submit del formulario, deshabilitar el botón para evitar múltiples envíos
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
</script>