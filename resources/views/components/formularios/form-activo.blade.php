@php
    $esEdicion = request()->is('*editar*') || isset($activo->act_id);
@endphp

<form action="#" method="POST" enctype="multipart/form-data" class="formulario-dinamico">
    @csrf
    @if($esEdicion)
        @method('PUT')
    @endif

    {{-- NOMBRE DEL ACTIVO --}}
    <div class="post-form">
        <label for="act_nombre">Nombre del Activo *</label>
        <input type="text" id="act_nombre" name="act_nombre" required maxlength="50"
               value="{{ old('act_nombre', $activo->act_nombre ?? '') }}"
               placeholder="Ej. Videobeam Epson">
    </div>

    {{-- SERIAL --}}
    <div class="post-form">
        <label for="act_serial">Número de Serial *</label>
        <input type="text" id="act_serial" name="act_serial" required maxlength="255"
               value="{{ old('act_serial', $activo->act_serial ?? '') }}"
               placeholder="Ej. SER123456" {{ $esEdicion ? 'readonly' : '' }}>
    </div>

    {{-- FOTO DEL ACTIVO --}}
    <div class="post-form">
        <label for="act_foto">Fotografía del Activo</label>
        <input type="file" id="act_foto" name="act_foto" accept="image/*">
        @if($esEdicion && isset($activo->act_foto))
            <small class="texto-aviso-foto">
                📸 Ya hay una foto registrada. Selecciona otra solo si deseas cambiarla.
            </small>
        @endif
    </div>

    {{-- MARCA --}}
    <div class="post-form">
        <label for="act_marca">Marca</label>
        <input type="text" id="act_marca" name="act_marca" maxlength="50"
               value="{{ old('act_marca', $activo->act_marca ?? '') }}">
    </div>

    {{-- ESTADO FÍSICO --}}
    <div class="post-form">
        <label for="act_estado_fisico">Estado Físico *</label>
        <select name="act_estado_fisico" id="act_estado_fisico" required>
            <option value="Excelente" {{ old('act_estado_fisico', $activo->act_estado_fisico ?? '') == 'Excelente' ? 'selected' : '' }}>Excelente</option>
            <option value="Bueno" {{ old('act_estado_fisico', $activo->act_estado_fisico ?? '') == 'Bueno' ? 'selected' : '' }}>Bueno</option>
            <option value="Regular" {{ old('act_estado_fisico', $activo->act_estado_fisico ?? '') == 'Regular' ? 'selected' : '' }}>Regular</option>
            <option value="Malo" {{ old('act_estado_fisico', $activo->act_estado_fisico ?? '') == 'Malo' ? 'selected' : '' }}>Malo</option>
        </select>
    </div>

    {{-- UBICACIÓN (AULA ASIGNADA) --}}
    <div class="post-form">
        <label for="aula_id">Ubicación (Aula Asignada) *</label>
        <select name="aula_id" id="aula_id" required>
            <option value="">-- Selecciona el Aula --</option>
            @foreach($aulas ?? [] as $a)
                <option value="{{ $a->aula_id }}" {{ old('aula_id', $activo->aula_id ?? '') == $a->aula_id ? 'selected' : '' }}>
                    {{ $a->aula_nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="contenedor-botones">
        <x-botones.boton type="button" class="btn-siger-accion btn-rojo" onclick="window.location.href='{{ url('/activos') }}'">
            Cancelar
        </x-botones.boton>

        <x-botones.boton type="submit" class="btn-siger-accion btn-verde">
            {{ $esEdicion ? 'Guardar Cambios' : 'Registrar Activo' }}
        </x-botones.boton>
    </div>
</form>