<<<<<<< HEAD
@props([
    'modo' => 'crear',
    'usuario' => null,
    'roles' => []
])

@if('crear' === $modo || 'editar-admin' === $modo)
    {{-- ROL: Dinámico desde la base de datos --}} 
    <div class="post-form">
        <label for="rol">Rol del Usuario <span class="text-danger">*</span></label>
        <select name="rol" id="rol" required>
            <option value="">-- Selecciona un Rol --</option>
            {{-- Si la variable $roles viene desde el controlador, la iteramos dinámicamente --}}
            @if(isset($roles) && count($roles) > 0)
                @foreach($roles as $r)
                    <option value="{{ $r->ROL_ID ?? $r->id }}" 
                        {{ (old('rol', $usuario->ROL_ID ?? '') == ($r->ROL_ID ?? $r->id)) ? 'selected' : '' }}>
                        {{ $r->ROL_NOMBRE ?? $r->name }}
                    </option>
                @endforeach
            @else
                {{-- Opciones de respaldo en caso de que no se pase la variable $roles --}}
                <option value="1" {{ old('rol', $usuario->ROL_ID ?? '') == '1' ? 'selected' : '' }}>Secretaría</option>
                <option value="2" {{ old('rol', $usuario->ROL_ID ?? '') == '2' ? 'selected' : '' }}>Rector(a)</option>
                <option value="3" {{ old('rol', $usuario->ROL_ID ?? '') == '3' ? 'selected' : '' }}>Docente</option>
            @endif
=======
{{-- ROL: Solo se muestra si estamos CREANDO un usuario (cuando no hay ID de usuario existente) --}}
@if(!request()->is('*perfil*'))
    <div class="post-form">
        <label for="rol">Rol</label>
        <select name="rol" id="rol">
            <option value="">--Selecciona--</option>
            <option value="2" {{ old('rol', $usuario->rol ?? '') == '2' ? 'selected' : '' }}>Docente</option>
            <option value="3" {{ old('rol', $usuario->rol ?? '') == '3' ? 'selected' : '' }}>Rector(a)</option>
>>>>>>> origin/backend-Elias
        </select>
    </div>
@endif

{{-- PRIMER NOMBRE --}}
<div class="post-form">
    <label for="name">Primer Nombre <span class="text-danger">*</span></label>
    <input type="text" id="name" name="name" required 
<<<<<<< HEAD
           value="{{ old('name', $usuario->USU_PRIMER_NOMBRE ?? '') }}">
=======
           value="{{ old('name', $usuario->name ?? '') }}">
>>>>>>> origin/backend-Elias
</div>

{{-- SEGUNDO NOMBRE --}}
<div class="post-form">
    <label for="second-name">Segundo Nombre</label>
    <input type="text" id="second-name" name="second-name" 
<<<<<<< HEAD
           value="{{ old('second-name', $usuario->USU_SEGUNDO_NOMBRE ?? '') }}">
=======
           value="{{ old('second-name', $usuario->second_name ?? '') }}">
>>>>>>> origin/backend-Elias
</div>

{{-- PRIMER APELLIDO --}}
<div class="post-form">
    <label for="lastname">Primer Apellido <span class="text-danger">*</span></label>
    <input type="text" id="lastname" name="lastname" required 
<<<<<<< HEAD
           value="{{ old('lastname', $usuario->USU_PRIMER_APELLIDO ?? '') }}">
=======
           value="{{ old('lastname', $usuario->lastname ?? '') }}">
>>>>>>> origin/backend-Elias
</div>

{{-- SEGUNDO APELLIDO --}}
<div class="post-form">
    <label for="second-last-name">Segundo Apellido</label>
    <input type="text" id="second-last-name" name="second-last-name" 
<<<<<<< HEAD
           value="{{ old('second-last-name', $usuario->USU_SEGUNDO_APELLIDO ?? '') }}">
</div>

@if('crear' === $modo || 'editar-admin' === $modo)
    {{-- ESTADO: Solo visible para crear o editar --}}
    <div class="post-form">
        <label for="estado">Estado <span class="text-danger">*</span></label>
        <select name="estado" id="estado">
            <option value="Activo" {{ old('estado', $usuario->USU_ESTADO ?? 'Activo') == 'Activo' ? 'selected' : '' }}>Activo</option>
            <option value="Inactivo" {{ old('estado', $usuario->USU_ESTADO ?? '') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
        </select>
    </div>
@endif

@if('crear' === $modo || 'editar-admin' === $modo)
    {{-- CÉDULA --}}
    <div class="post-form">
        <label for="identificacion">Cédula / Documento <span class="text-danger">*</span></label>
        <input type="text" id="identificacion" name="identificacion" required
               value="{{ old('identificacion', $usuario->USU_CEDULA ?? '') }}"
               {{ isset($usuario->USU_ID) ? 'readonly' : '' }}>
    </div>
@endif

{{-- CORREO --}}
<div class="post-form">
    <label for="correo">Correo Electrónico <span class="text-danger">*</span></label>
    <input type="email" id="correo" name="correo" required
           value="{{ old('correo', $usuario->USU_CORREO ?? '') }}">
</div>

{{-- CONDICIONAL CONTRASEÑA: Solo si se está CREANDO un usuario --}}
@if('crear' === $modo)
=======
           value="{{ old('second-last-name', $usuario->second_last_name ?? '') }}">
</div>

{{-- CÉDULA --}}
<div class="post-form">
    <label for="identificacion">Cédula <span class="text-danger">*</span></label>
    <input type="text" id="identificacion" name="identificacion" 
           value="{{ old('identificacion', $usuario->identificacion ?? '') }}"
           {{ isset($usuario->id) ? 'readonly' : '' }}>
</div>

{{-- CORREO --}}
<div class="post-form">
    <label for="correo">Correo <span class="text-danger">*</span></label>
    <input type="email" id="correo" name="correo" 
           value="{{ old('correo', $usuario->correo ?? '') }}">
</div>

{{-- CONDICIONAL CONTRASEÑA: Solo si se está CREANDO un usuario (sin ID en BD) --}}
@if(!request()->is('*perfil*'))
>>>>>>> origin/backend-Elias
    <div class="post-form">
        <label for="password">Contraseña Inicial <span class="text-danger">*</span></label>
        <input type="password" id="password" name="password" required 
               autocomplete="new-password" placeholder="Asigna una clave temporal">
    </div>
@endif

<<<<<<< HEAD
<div class="contenedor-botones" style="margin-top: 1.5rem; display: flex; gap: 1rem;">
=======
<div class="contenedor-botones">
>>>>>>> origin/backend-Elias
    <x-botones.boton 
        class="btn btn-rojo" 
        type="button" 
        onclick="ejecutarCierreUniversal(this)">
        Cancelar
    </x-botones.boton>

    <x-botones.boton class="btn btn-verde" type="submit">
<<<<<<< HEAD
        @if('perfil' === $modo || 'editar-admin' === $modo || 'editar' === $modo)
            Guardar Cambios
        @else
            Registrar
=======
        @if(request()->is('*perfil*')|| request()->is('*editar*'))
            Guardar Cambios {{-- Si la URL es de perfil o editar, muestra esto --}}
        @else
            Registrar {{-- Para cualquier otra vista (Crear), muestra esto --}}
>>>>>>> origin/backend-Elias
        @endif
    </x-botones.boton>
</div>

<script>
function ejecutarCierreUniversal(boton) {
<<<<<<< HEAD
    let formulario = boton.closest('form');
    if (formulario) formulario.reset();

=======
    // 1. Siempre limpiamos los inputs primero
    let formulario = boton.closest('form');
    if (formulario) formulario.reset();

    // 2. Buscamos el contenedor padre (sirve para ambos diseños)
>>>>>>> origin/backend-Elias
    let contenedor = boton.closest('.collapse') 
                  || boton.closest('#contenedor-formulario') 
                  || boton.closest('.formulario-desplegable');
    
    if (contenedor) {
<<<<<<< HEAD
        contenedor.classList.remove('activo');
        contenedor.classList.remove('show');
        
=======
        // Quitamos la clase del perfil personalizado
        contenedor.classList.remove('activo');
        
        // Quitamos las clases de Bootstrap por si acaso
        contenedor.classList.remove('show');
        
        // Cierre nativo mediante la API de Bootstrap si está presente
>>>>>>> origin/backend-Elias
        if (window.bootstrap && bootstrap.Collapse) {
            let bsCollapse = bootstrap.Collapse.getInstance(contenedor);
            if (bsCollapse) {
                bsCollapse.hide();
            }
        }
        
<<<<<<< HEAD
=======
        // Si estamos en móvil, aseguramos el comportamiento visual directo
>>>>>>> origin/backend-Elias
        if (window.innerWidth <= 768) {
            contenedor.style.display = 'none';
        }
    }
}
<<<<<<< HEAD
</script>   
=======
</script>
>>>>>>> origin/backend-Elias
