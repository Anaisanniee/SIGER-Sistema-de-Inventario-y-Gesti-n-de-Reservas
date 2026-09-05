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

{{--
    BLOQUE DE CONTRASEÑA: Solo visible en los modos de creación, edición por administrador o edición de perfil
    - En el modo de creación, la contraseña es obligatoria.
    - En el modo de edición por administrador, la contraseña es opcional.
    - En el modo de edición de perfil, la contraseña es opcional y se puede cambiar
--}}

@if('crear' === $modo || 'editar-admin' === $modo)

    <div class="post-form">
        <label for="password">
            Contraseña @if('crear' === $modo)<span class="text-danger">*</span>@endif
        </label>
        
        <input type="password" 
               id="password" 
               name="password" 
               class="input-siger"
               autocomplete="new-password"
               placeholder="{{ ('editar-admin' === $modo && isset($usuario)) ? 'Puedes cambiar en cualquier momento la clave del usuario' : 'Asigna una clave de ingreso' }}">
</div>
@endif


<div class="contenedor-botones" style="margin-top: 1.5rem; display: flex; gap: 1rem;">
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
</script>