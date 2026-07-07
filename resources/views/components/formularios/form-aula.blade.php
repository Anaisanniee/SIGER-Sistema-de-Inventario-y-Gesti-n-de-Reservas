@php
    $esEdicion = request()->is('*editar*') || isset($aula->aula_id);
@endphp

<form action="#" method="POST" enctype="multipart/form-data" class="formulario-dinamico">
    @csrf
    @if($esEdicion)
        @method('PUT')
    @endif

    {{-- NOMBRE DEL AULA --}}
    <div class="post-form">
        <label for="aula_nombre">Nombre del Aula / Espacio *</label>
        <input type="text" id="aula_nombre" name="aula_nombre" required maxlength="25"
               value="{{ old('aula_nombre', $aula->aula_nombre ?? '') }}"
               placeholder="Ej. Laboratorio A, Aula 102">
    </div>

    {{-- TIPO DE AULA (Llave foránea mapeada de la BD) --}}
    <div class="post-form">
        <label for="tip_aula_id">Categoría / Tipo de Aula *</label>
        <select name="tip_aula_id" id="tip_aula_id" required>
            <option value="">-- Selecciona el Tipo --</option>
            <option value="1" {{ old('tip_aula_id', $aula->tip_aula_id ?? '') == '1' ? 'selected' : '' }}>Aula Teórica</option>
            <option value="2" {{ old('tip_aula_id', $aula->tip_aula_id ?? '') == '2' ? 'selected' : '' }}>Laboratorio de Química</option>
            <option value="3" {{ old('tip_aula_id', $aula->tip_aula_id ?? '') == '3' ? 'selected' : '' }}>Sala de Sistemas</option>
        </select>
    </div>

    {{-- CAPACIDAD --}}
    <div class="post-form">
        <label for="aula_capacidad">Capacidad (Personas) *</label>
        <input type="number" id="aula_capacidad" name="aula_capacidad" required
               value="{{ old('aula_capacidad', $aula->aula_capacidad ?? '') }}"
               placeholder="Ej. 30">
    </div>

    {{-- 📸 FOTOGRAFÍA DEL AULA / ESPACIO --}}
    <div class="post-form">
        <label for="aula_foto">Fotografía del Aula / Espacio</label>
        <input type="file" id="aula_foto" name="aula_foto" accept="image/*">
        @if($esEdicion && isset($aula->aula_foto))
            <small class="texto-aviso-foto">
                📸 Ya hay una foto registrada. Selecciona otra solo si deseas cambiarla.
            </small>
        @endif
    </div>

    {{-- ESTADO --}}
    <div class="post-form">
        <label for="aula_estado">Estado Inicial *</label>
        <select name="aula_estado" id="aula_estado" required>
            <option value="Disponible" {{ old('aula_estado', $aula->aula_estado ?? '') == 'Disponible' ? 'selected' : '' }}>Disponible</option>
            <option value="Mantenimiento" {{ old('aula_estado', $aula->aula_estado ?? '') == 'Mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
        </select>
    </div>

    <div class="post-form-switch">
        <span class="label-switch">¿Está disponible para reservas?</span>
        <label class="switch-contenedor" for="aula_reservable">
            <input type="checkbox" id="aula_reservable" name="aula_reservable" value="1"
                   {{ old('aula_reservable', $aula->aula_reservable ?? '1') == '1' ? 'checked' : '' }}>
            <span class="switch-slider"></span>
        </label>
    </div>

    {{-- BOTONES DE ACCIÓN --}}

    @if($esEdicion)
        {{-- MOTIVO DE BAJA (Sólo en edición, tal cual tu migración) --}}
        <div class="post-form">
            <label for="aula_motivo_baja">Motivo de Baja (Opcional)</label>
            <input type="text" id="aula_motivo_baja" name="aula_motivo_baja" maxlength="255"
                   value="{{ old('aula_motivo_baja', $aula->aula_motivo_baja ?? '') }}">
        </div>
    @endif

    <div class="contenedor-botones">
        <x-botones.boton type="button" class="btn-siger-accion btn-rojo" onclick="window.location.href='{{ url('/aulas') }}'">
            Cancelar
        </x-botones.boton>

        <x-botones.boton type="submit" class="btn-siger-accion btn-verde">
            {{ $esEdicion ? 'Guardar Cambios' : 'Registrar Aula' }}
        </x-botones.boton>
    </div>
</form>