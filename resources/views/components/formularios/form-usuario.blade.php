@props([
    'modo' => 'crear',
    'usuario' => null,
    'roles' => [],
    'secretariaOcupada' => false,
    'rectorOcupado' => false
])

@if('crear' === $modo || 'editar-admin' === $modo)
    {{-- ROL: Dinámico desde la base de datos --}} 
    <div class="post-form">
        <label for="rol">Rol del Usuario <span class="text-danger">*</span></label>
        <select name="rol" id="rol" required>
            <option value="">-- Selecciona un Rol --</option>
            @if(isset($roles) && count($roles) > 0)
                @foreach($roles as $r)
                    @php
                        $nombreRol = strtolower($r->ROL_NOMBRE ?? $r->name ?? '');
                        $esSecretaria = in_array($nombreRol, ['secretaria', 'secretario']);
                        $esRector = in_array($nombreRol, ['rectora', 'rector']);
                        
                        // Validamos si debe deshabilitarse
                        $deshabilitado = ($esSecretaria && $secretariaOcupada) || ($esRector && $rectorOcupado);
                    @endphp

                    <option value="{{ $r->ROL_ID ?? $r->id }}" 
                        {{ $deshabilitado ? 'disabled' : '' }}
                        {{ (old('rol', $usuario->ROL_ID ?? '') == ($r->ROL_ID ?? $r->id)) ? 'selected' : '' }}>
                        {{ $r->ROL_NOMBRE ?? $r->name }} {{ $deshabilitado ? '(Ya asignado)' : '' }}
                    </option>
                @endforeach
            @else
                <option value="1" {{ $secretariaOcupada ? 'disabled' : '' }} {{ old('rol', $usuario->ROL_ID ?? '') == '1' ? 'selected' : '' }}>Secretaría {{ $secretariaOcupada ? '(Ya asignado)' : '' }}</option>
                <option value="2" {{ $rectorOcupado ? 'disabled' : '' }} {{ old('rol', $usuario->ROL_ID ?? '') == '2' ? 'selected' : '' }}>Rector(a) {{ $rectorOcupado ? '(Ya asignado)' : '' }}</option>
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
    {{-- ESTADO --}}
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

{{-- GESTIÓN DINÁMICA DE CONTRASEÑA SEGÚN MODO --}}
@if('crear' === $modo)
    <div class="bloque-gestion-clave">
        <label class="etiqueta-gestion-clave">
            <i class="fas fa-key"></i> Clave de Acceso Inicial
        </label>
        <p class="texto-gestion-clave">
            La contraseña de ingreso se asignará automáticamente utilizando el número de <strong>Cédula / Documento</strong> registrado. El usuario podrá cambiarla desde su perfil tras iniciar sesión.
        </p>
    </div>
@elseif('editar-admin' === $modo || 'editar' === $modo)
    <div class="bloque-gestion-clave">
        <label class="etiqueta-gestion-clave">
            <i class="fas fa-key"></i> Gestión de Contraseña
        </label>
        <div class="opcion-restablecer-clave">
            <input type="checkbox" name="restablecer_a_cedula" id="restablecer_a_cedula" class="check-restablecer" value="1">
            <label for="restablecer_a_cedula" class="label-restablecer">
                Restablecer contraseña al número de documento (<strong>{{ $usuario->USU_CEDULA ?? 'Documento Registrado' }}</strong>)
            </label>
        </div>
        <span class="subtexto-gestion-clave">
            Al activar esta opción, la clave actual se descartará y se restablecerá al número de cédula del usuario.
        </span>
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
    if (formulario) {
        formulario.reset();
    }

    // El colapso/desplegable SOLO aplica para Móviles y Tablets (<= 1024px)
    if (window.innerWidth <= 1024) {
        let contenedor = boton.closest('.collapse') 
                    || boton.closest('#contenedor-formulario') 
                    || boton.closest('.formulario-desplegable');
        
        if (contenedor) {
            contenedor.classList.remove('activo', 'show');
            
            if (window.bootstrap && bootstrap.Collapse) {
                let bsCollapse = bootstrap.Collapse.getInstance(contenedor);
                if (bsCollapse) {
                    bsCollapse.hide();
                }
            }
            
            contenedor.style.display = 'none';
        }
    }
}
</script>