{{-- ROL: Solo se muestra si estamos CREANDO un usuario (cuando no hay ID de usuario existente) --}}
@if(!request()->is('*perfil*'))
    <div class="post-form">
        <label for="rol">Rol <span class="text-danger">*</span></label>
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

{{-- CÉDULA --}}
<div class="post-form">
    <label for="identificacion">Cédula <span class="text-danger">*</span></label>
    <input type="text" id="identificacion" name="identificacion" required
           value="{{ old('identificacion', $usuario->identificacion ?? '') }}"
           {{ isset($usuario->id) ? 'readonly' : '' }}>
</div>

{{-- CORREO --}}
<div class="post-form">
    <label for="correo">Correo <span class="text-danger">*</span></label>
    <input type="email" id="correo" name="correo" required
           value="{{ old('correo', $usuario->correo ?? '') }}">
</div>

{{-- CONDICIONAL CONTRASEÑA: Solo si se está CREANDO un usuario (sin ID en BD) --}}
@if(!request()->is('*perfil*'))
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
        @if(request()->is('*perfil*')|| request()->is('*editar*'))
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
</script>