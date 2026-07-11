@php
    $esEdicion = request()->is('*editar*') || isset($aula->aula_id);
@endphp

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

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
        <label for="aula_nombre">Nombre del Aula / Espacio *</label>
        <input type="text" id="aula_nombre" name="aula_nombre"
               value="{{ old('aula_nombre', $aula->aula_nombre ?? '') }}"
               placeholder="Ej. Laboratorio A, Aula 102">
    </div>

    {{-- TIPO DE AULA (Llave foránea mapeada de la BD) --}}
    <div class="post-form">
        <label for="tip_aula_id">Categoría / Tipo de Aula *</label>
        <select name="tip_aula_id" id="tip_aula_id">
            @foreach($tipos as $tipo)
                <option value="{{ $tipo->tip_aula_id }}" 
                    {{ old('tip_aula_id', $aula->tip_aula_id ?? '') == $tipo->tip_aula_id ? 'selected' : '' }}>
                    {{ $tipo->tip_aula_nombre }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- CAPACIDAD --}}
    <div class="post-form">
        <label for="aula_capacidad">Capacidad (Personas) *</label>
        <input type="number" id="aula_capacidad" name="aula_capacidad"
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
        <select name="aula_estado" id="aula_estado">
            <option value="Disponible" {{ old('aula_estado', $aula->aula_estado ?? '') == 'Disponible' ? 'selected' : '' }}>Disponible</option>
            <option value="Mantenimiento" {{ old('aula_estado', $aula->aula_estado ?? '') == 'Mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
        </select>
    </div>

    <div class="post-form-switch" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
    
        <!-- Texto a la izquierda -->
        <span class="label-switch">¿Está disponible para reservas?</span>
        
        <!-- Switch a la derecha -->
        <label class="switch-contenedor" for="aula_reservable" style="margin-bottom: 0;">
            <input type="checkbox" id="aula_reservable" name="aula_reservable" value="1"
                {{ old('aula_reservable', $aula->aula_reservable ?? '1') == '1' ? 'checked' : '' }}>
            <span class="switch-slider"></span>
        </label>
        
    </div>
    {{-- BOTONES DE ACCIÓN --}}

    <div class="contenedor-botones">
        <x-botones.boton type="button" class="btn-siger-accion btn-rojo" onclick="window.location.href='{{ url('/inventario') }}'">
            Cancelar
        </x-botones.boton>

        <x-botones.boton type="submit" class="btn-siger-accion btn-verde">
            {{ $esEdicion ? 'Guardar Cambios' : 'Registrar Aula' }}
        </x-botones.boton>
    </div>
</form>