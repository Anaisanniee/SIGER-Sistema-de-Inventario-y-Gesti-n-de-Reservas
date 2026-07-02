{{-- ROL --}}
@if(!isset($usuario))
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
    <label for="name">Primer Nombre</label>
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
    <label for="lastname">Primer Apellido</label>
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
    <label for="identificacion">Cédula</label>
    <input type="text" id="identificacion" name="identificacion" 
           value="{{ old('identificacion', $usuario->identificacion ?? '') }}"
           {{ isset($usuario) ? 'readonly' : '' }}>
</div>

{{-- CORREO --}}
<div class="post-form">
    <label for="correo">Correo</label>
    <input type="email" id="correo" name="correo" 
           value="{{ old('correo', $usuario->correo ?? '') }}">
</div>

{{-- CONDICIONAL: SI NO EXISTE UN USUARIO (MODO CREAR), SOLICITA LA CONTRASEÑA EN ESTA VERSIÓN BETA --}}
@if(!isset($usuario))
    <div class="post-form">
        <label for="password">Contraseña Inicial *</label>
        <input type="password" id="password" name="password" required 
               placeholder="Asigna una clave temporal">
    </div>
@endif

<div class="contenedor-botones">
<x-botones.boton 
    class="btn btn-rojo" 
    type="button" 
    onclick="if(window.innerWidth <= 768) {
        // Buscamos cualquier ancestro con la clase 'collapse'
        let contenedor = this.closest('.collapse');
        
        if(contenedor) {
            // 1. Limpiamos el formulario primero
            let form = this.closest('form');
            if(form) form.reset();

            // 2. Si Bootstrap está cargado de forma global, usamos su método nativo
            if(window.bootstrap && bootstrap.Collapse) {
                let bsCollapse = bootstrap.Collapse.getInstance(contenedor) || new bootstrap.Collapse(contenedor, { toggle: false });
                bsCollapse.hide();
            } else {
                // 3. Plan de contingencia: Si Bootstrap falla, lo removemos con CSS puro e inmediato
                contenedor.classList.remove('show');
                contenedor.style.display = 'none';
            }
        }
    } else {
        let form = this.closest('form');
        if(form) form.reset();
    }">
    Cancelar
</x-botones.boton>

<x-botones.boton class="btn btn-verde" type="submit">
        @if(isset($usuario))
            Guardar Cambios {{-- Si hay usuario (Perfil), dice esto --}}
        @else
            Registrar {{-- Si no hay usuario (Crear), dice esto --}}
        @endif
</x-botones.boton>
</div>


