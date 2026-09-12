@php
    $esEdicion = request()->is('*editar*') || isset($aula->aula_id);
@endphp

<form action="{{ $esEdicion ? route('aulas.update', $aula->aula_id) : route('aulas.store') }}" 
      method="POST" 
      enctype="multipart/form-data" 
      class="formulario-dinamico">
    @csrf
    @if($esEdicion)
        @method('PUT')
    @endif

    {{-- NOMBRE DEL AULA --}}
    <div class="post-form">
        <label for="aula_nombre">Nombre del Aula / Espacio <span class="text-danger">*</span></label>
        <input type="text" id="aula_nombre" name="aula_nombre"
               value="{{ old('aula_nombre', $aula->aula_nombre ?? '') }}"
               class="@error('aula_nombre') is-invalid @enderror"
               placeholder="Ej. Laboratorio A, Aula 102"
               maxlength="25"
               pattern=".*\S+.*"
               title="El nombre no puede estar vacío ni contener únicamente espacios en blanco."
               required>
        @error('aula_nombre') 
            <div class="text-danger small">{{ $message }}</div> 
        @enderror
    </div>

    {{-- TIPO DE AULA --}}
    <div class="post-form">
        <label for="tip_aula_id">Tipo de Aula <span class="text-danger">*</span></label>
        <select name="tip_aula_id" id="tip_aula_id" class="@error('tip_aula_id') is-invalid @enderror" required>
            <option value="">-- Selecciona el tipo de aula --</option>
            @foreach($tipos ?? [] as $tipo)
                <option value="{{ $tipo->tip_aula_id }}" 
                    {{ old('tip_aula_id', $aula->tip_aula_id ?? '') == $tipo->tip_aula_id ? 'selected' : '' }}>
                    {{ $tipo->tip_aula_nombre }}
                </option>
            @endforeach
        </select>
        @error('tip_aula_id') 
            <div class="text-danger small">{{ $message }}</div> 
        @enderror
    </div>

    {{-- CAPACIDAD --}}
    <div class="post-form">
        <label for="aula_capacidad">Capacidad (Personas) <span class="text-danger">*</span></label>
        <input type="number" id="aula_capacidad" name="aula_capacidad"
               value="{{ old('aula_capacidad', $aula->aula_capacidad ?? '') }}"
               class="@error('aula_capacidad') is-invalid @enderror"
               placeholder="Ej. 30"
               min="1"
               required>
        @error('aula_capacidad') 
            <div class="text-danger small">{{ $message }}</div> 
        @enderror
    </div>

    {{-- FOTOGRAFÍA DEL AULA / ESPACIO --}}
    <div class="post-form">
        <label for="aula_foto">Fotografía del Aula / Espacio</label>
        <input type="file" id="aula_foto" name="aula_foto" accept="image/*">
        @error('aula_foto') 
            <div class="text-danger small">{{ $error }}</div> 
        @enderror

        @if($esEdicion && isset($aula->aula_foto))
             <x-alertas.notificacion tipo="info">
                 Ya se encuentra una foto registrada. Selecciona otra solo si deseas cambiarla.
             </x-alertas.notificacion>
        @endif
    </div>

    {{-- ESTADO --}}
    <div class="post-form">
        <label for="aula_estado">
            {{ $esEdicion ? 'Estado Físico Actual' : 'Estado Físico Inicial' }} 
            <span class="text-danger">*</span>
        </label>
        
        <select name="aula_estado" id="aula_estado" class="@error('aula_estado') is-invalid @enderror" required>
            <option value="Buen estado" {{ old('aula_estado', $aula->aula_estado ?? '') == 'Buen estado' ? 'selected' : '' }}>Buen estado</option>
            <option value="En Mantenimiento" {{ old('aula_estado', $aula->aula_estado ?? '') == 'En Mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
            <option value="Dañado" {{ old('aula_estado', $aula->aula_estado ?? '') == 'Dañado' ? 'selected' : '' }}>Dañado</option>
        </select>
        @error('aula_estado') 
            <div class="text-danger small">{{ $message }}</div> 
        @enderror
    </div>

    {{-- DISPONIBILIDAD PARA RESERVAS --}}
    <div class="post-form-switch" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
        <span class="label-switch">¿Está disponible para reservas? <span class="text-danger">*</span></span>
        
        <label class="switch-contenedor" for="aula_reservable" style="margin-bottom: 0;">
            <input type="hidden" name="aula_reservable" value="0">
            <input type="checkbox" id="aula_reservable" name="aula_reservable" value="1"
                {{ old('aula_reservable', $aula->aula_reservable ?? '1') == '1' ? 'checked' : '' }}>
            <span class="switch-slider"></span>
        </label>
    </div>

    {{-- BOTONES DE ACCIÓN --}}
    <div class="contenedor-botones">
        <x-botones.boton type="button" class="btn-siger-accion btn btn-rojo" onclick="window.location.href='{{ url('/inventario') }}'">
            Cancelar
        </x-botones.boton>

        <x-botones.boton type="submit" class="btn-siger-accion btn">
            {{ $esEdicion ? 'Guardar Cambios' : 'Registrar Aula' }}
        </x-botones.boton>
    </div>
</form>