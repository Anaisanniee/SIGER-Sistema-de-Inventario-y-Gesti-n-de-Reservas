@php
    $esEdicion = request()->is('*editar*') || isset($activo->act_id);
@endphp

@if ($errors->any())
    <div class="alert alert-danger" style="color: #a94442; background-color: #f2dede; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger" style="color: #a94442; background-color: #f2dede; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
        {{ session('error') }}
    </div>
@endif

<form action="{{ $esEdicion ? route('activos.update', $activo->act_id) : route('activos.store') }}" 
      method="POST" 
      enctype="multipart/form-data" 
      class="formulario-dinamico">
    @csrf
    @if($esEdicion)
        @method('PUT')
    @endif

    {{-- NOMBRE DEL ACTIVO --}}
    <div class="post-form">
        <label for="act_nombre">Nombre del Activo <span class="text-danger">*</span></label>
        <input type="text" id="act_nombre" name="act_nombre"
               value="{{ old('act_nombre', $activo->act_nombre ?? '') }}"
               class="@error('act_nombre') is-invalid @enderror"
               placeholder="Ej. Videobeam Epson">
        @error('act_nombre') 
            <div class="text-danger small">{{ $message }}</div> 
        @enderror
    </div>

    {{-- SERIAL --}}
    <div class="post-form">
        <label for="act_serial">Número de Serial <span class="text-danger">*</span></label>
        <input type="text" id="act_serial" name="act_serial"
               value="{{ old('act_serial', $activo->act_serial ?? '') }}"
               class="@error('act_serial') is-invalid @enderror"
               placeholder="Ej. SER123456">
        @error('act_serial') 
            <div class="text-danger small">{{ $message }}</div> 
        @enderror
    </div>

    {{-- FOTO DEL ACTIVO --}}
    <div class="post-form">
        <label for="act_foto">Fotografía del Activo <span class="text-danger">*</span></label>
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
               value="{{ old('act_marca', $activo->act_marca ?? '') }}"
               class="@error('act_marca') is-invalid @enderror" 
               placeholder="Ej: Dell, HP, Epson">
    </div>

    <div class="post-form">
        <label for="his_pre_valor">Precio</label>
        <input type="number" 
            step="0.01" 
            min="0" 
            id="his_pre_valor" 
            name="his_pre_valor" 
            maxlength="50"
            value="{{ old('his_pre_valor', $activo->his_pre_valor ?? '') }}"
            class="@error('his_pre_valor') is-invalid @enderror"
            placeholder="Ej: 1500000">
    </div>

    {{-- ESTADO FÍSICO --}}
    <div class="post-form">
        <label for="act_estado_fisico">Estado Físico <span class="text-danger">*</span></label>
        <select name="act_estado_fisico" id="act_estado_fisico" required>
            <option value="Excelente" {{ old('act_estado_fisico', $activo->act_estado_fisico ?? '') == 'Excelente' ? 'selected' : '' }}>Excelente</option>
            <option value="Bueno" {{ old('act_estado_fisico', $activo->act_estado_fisico ?? '') == 'Bueno' ? 'selected' : '' }}>Bueno</option>
            <option value="Regular" {{ old('act_estado_fisico', $activo->act_estado_fisico ?? '') == 'Regular' ? 'selected' : '' }}>Regular</option>
            <option value="Malo" {{ old('act_estado_fisico', $activo->act_estado_fisico ?? '') == 'Malo' ? 'selected' : '' }}>Malo</option>
        </select>
    </div>

    {{-- UBICACIÓN (AULA ASIGNADA) --}}
    <div class="post-form">
        <label for="aula_id">Ubicación (Aula Asignada) <span class="text-danger">*</span></label>
        <select name="aula_id" id="aula_id">
            <option value="">-- Selecciona el Aula --</option>
            @foreach($aulas ?? [] as $a)
                <option value="{{ $a->aula_id }}" {{ old('aula_id', $activo->aula_id ?? '') == $a->aula_id ? 'selected' : '' }}>
                    {{ $a->aula_nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="post-form">
        <label for="cate_id">Categoria del equipo <span class="text-danger">*</span></label> 
        <select name="cate_id" id="cate_id">
            <option value="">-- Selecciona la categoria --</option>
            @foreach($categorias ?? [] as $cate)
                <option value="{{ $cate->cate_id }}" {{ old('cate_id', $activo->cate_id ?? '') == $cate->cate_id ? 'selected' : '' }}>
                    {{ $cate->cate_nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="post-form">
        <label for="act_fecha_ingreso">Fecha de Ingreso <span class="text-danger">*</span></label>
        <input type="date" 
            id="act_fecha_ingreso" 
            name="act_fecha_ingreso" 
            value="{{ old('act_fecha_ingreso', isset($activo->act_fecha_ingreso) ? \Carbon\Carbon::parse($activo->act_fecha_ingreso)->format('Y-m-d') : date('Y-m-d')) }}" 
            class="form-control bg-light border-0 py-2 px-3 rounded-pill @error('act_fecha_ingreso') is-invalid @enderror"
            required>
            
        @error('act_fecha_ingreso')
            <span class="invalid-feedback" style="display: block; color: var(--color-estado-dañado); font-size: 0.875rem;">
                {{ $message }}
            </span>
        @enderror
    </div>

    <div class="post-form-switch">
        <span class="label-switch">¿Está disponible para reservas?</span>
        <label class="switch-contenedor" for="act_reservable">
            <input type="hidden" name="act_reservable" value="0">
            <input type="checkbox" id="act_reservable" name="act_reservable" value="1"
                   {{ old('act_reservable', $activo->act_reservable ?? '1') == '1' ? 'checked' : '' }}>
            <span class="switch-slider"></span>
        </label>
    </div>

    {{-- BOTONES DE ACCIÓN --}}

    <div class="contenedor-botones">
        <x-botones.boton type="button" class="btn-siger-accion btn-rojo" onclick="window.location.href='{{ url('/inventario') }}'">
            Cancelar
        </x-botones.boton>

        <x-botones.boton type="submit" class="btn-siger-accion btn-verde">
            {{ $esEdicion ? 'Guardar Cambios' : 'Registrar Activo' }}
        </x-botones.boton>
    </div>
</form>