@php
    $esEdicion = request()->is('*editar*') || isset($aula->aula_id);
@endphp

<<<<<<< HEAD
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
=======
>>>>>>> origin/backend-Elias

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
               placeholder="Ej. Laboratorio A, Aula 102">
    </div>

    {{-- TIPO DE AULA (Llave foránea mapeada de la BD) --}}
    <div class="post-form">
<<<<<<< HEAD
        <label for="tip_aula_id">Categoría / Tipo de Aula <span class="text-danger">*</span></label>
=======
        <label for="tip_aula_id">Tipo de Aula <span class="text-danger">*</span></label>
>>>>>>> origin/backend-Elias
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
        <label for="aula_capacidad">Capacidad (Personas) <span class="text-danger">*</span></label>
        <input type="number" id="aula_capacidad" name="aula_capacidad"
               value="{{ old('aula_capacidad', $aula->aula_capacidad ?? '') }}"
               placeholder="Ej. 30">
    </div>

<<<<<<< HEAD
    {{-- FOTOGRAFÍA DEL AULA / ESPACIO --}}
=======
    {{-- 📸 FOTOGRAFÍA DEL AULA / ESPACIO --}}
>>>>>>> origin/backend-Elias
    <div class="post-form">
        <label for="aula_foto">Fotografía del Aula / Espacio</label>
        <input type="file" id="aula_foto" name="aula_foto" accept="image/*">
        @if($esEdicion && isset($aula->aula_foto))
<<<<<<< HEAD
            <small class="texto-aviso-foto">
                📸 Ya hay una foto registrada. Selecciona otra solo si deseas cambiarla.
            </small>
=======
           <x-alertas.notificacion
                tipo="info"
            >Ya se encuentra una foto registrada. Selecciona otra solo si deseas cambiarla.</x-alertas.notificacion>
>>>>>>> origin/backend-Elias
        @endif
    </div>

    {{-- ESTADO --}}
    <div class="post-form">
        <label for="aula_estado">Estado Inicial <span class="text-danger">*</span></label>
        <select name="aula_estado" id="aula_estado">
            <option value="Disponible" {{ old('aula_estado', $aula->aula_estado ?? '') == 'Disponible' ? 'selected' : '' }}>Disponible</option>
            <option value="Mantenimiento" {{ old('aula_estado', $aula->aula_estado ?? '') == 'Mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
        </select>
    </div>

    <div class="post-form-switch" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
    
        <!-- Texto a la izquierda -->
<<<<<<< HEAD
        <span class="label-switch">¿Está disponible para reservas?</span>
=======
        <span class="label-switch">¿Está disponible para reservas? <span class="text-danger">*</span></span>
>>>>>>> origin/backend-Elias
        
        <!-- Switch a la derecha -->
        <label class="switch-contenedor" for="aula_reservable" style="margin-bottom: 0;">
            <input type="checkbox" id="aula_reservable" name="aula_reservable" value="1"
                {{ old('aula_reservable', $aula->aula_reservable ?? '1') == '1' ? 'checked' : '' }}>
            <span class="switch-slider"></span>
        </label>
        
    </div>
    {{-- BOTONES DE ACCIÓN --}}

    <div class="contenedor-botones">
<<<<<<< HEAD
        <x-botones.boton type="button" class="btn-siger-accion btn-rojo" onclick="window.location.href='{{ url('/inventario') }}'">
            Cancelar
        </x-botones.boton>

        <x-botones.boton type="submit" class="btn-siger-accion btn-verde">
=======
        <x-botones.boton type="button" class="btn-siger-accion btn btn-rojo" onclick="window.location.href='{{ url('/inventario') }}'">
            Cancelar
        </x-botones.boton>

        <x-botones.boton type="submit" class="btn-siger-accion btn">
>>>>>>> origin/backend-Elias
            {{ $esEdicion ? 'Guardar Cambios' : 'Registrar Aula' }}
        </x-botones.boton>
    </div>
</form>