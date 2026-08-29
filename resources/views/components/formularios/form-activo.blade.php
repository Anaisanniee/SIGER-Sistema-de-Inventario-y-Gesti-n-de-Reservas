@php
    $esEdicion = request()->is('*editar*') || isset($activo->act_id);
@endphp

<<<<<<< HEAD
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

=======
>>>>>>> origin/backend-Elias
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
<<<<<<< HEAD
               placeholder="Ej. Videobeam Epson">
=======
               placeholder="Ej. Videobeam Epson" required>
>>>>>>> origin/backend-Elias
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
<<<<<<< HEAD
               placeholder="Ej. SER123456">
=======
               placeholder="Ej. SER123456" required>
>>>>>>> origin/backend-Elias
        @error('act_serial') 
            <div class="text-danger small">{{ $message }}</div> 
        @enderror
    </div>

    {{-- FOTO DEL ACTIVO --}}
    <div class="post-form">
<<<<<<< HEAD
        <label for="act_foto">Fotografía del Activo <span class="text-danger">*</span></label>
        <input type="file" id="act_foto" name="act_foto" accept="image/*">
        @if($esEdicion && isset($activo->act_foto))
            <small class="texto-aviso-foto">
                📸 Ya hay una foto registrada. Selecciona otra solo si deseas cambiarla.
            </small>
=======
        <label for="act_foto">Fotografía del Activo</label>
        <input type="file" id="act_foto" name="act_foto" accept="image/*">
        @if($esEdicion && isset($activo->act_foto))
              <x-alertas.notificacion
                tipo="info"
            >Ya se encuentra una foto registrada. Selecciona otra solo si deseas cambiarla.</x-alertas.notificacion>
>>>>>>> origin/backend-Elias
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

<<<<<<< HEAD
    <div class="post-form">
        <label for="his_pre_valor">Precio</label>
=======
    {{-- PRECIO (Siempre visible) --}}
    <div class="post-form">
        <label for="his_pre_valor">Precio <span class="text-danger">*</span></label>
>>>>>>> origin/backend-Elias
        <input type="number" 
            step="0.01" 
            min="0" 
            id="his_pre_valor" 
            name="his_pre_valor" 
<<<<<<< HEAD
            maxlength="50"
            value="{{ old('his_pre_valor', $activo->his_pre_valor ?? '') }}"
=======
            value="{{ old('his_pre_valor', isset($activo) ? optional($activo->historialPrecios->sortByDesc('his_pre_fecha_cambio')->first())->his_pre_valor : '') }}"
>>>>>>> origin/backend-Elias
            class="@error('his_pre_valor') is-invalid @enderror"
            placeholder="Ej: 1500000">
    </div>

<<<<<<< HEAD
=======
    {{-- MOTIVO (Solo visible en edición) --}}
    @if($esEdicion)
    <div class="post-form">
        <label for="his_pre_motivo">Motivo del Cambio de Precio <span class="text-danger">*</span></label>
        <input type="text" 
            id="his_pre_motivo" 
            name="his_pre_motivo" 
            value="{{ old('his_pre_motivo') }}"
            class="@error('his_pre_motivo') is-invalid @enderror"
            placeholder="Ej: Mantenimiento, reavalúo, error de registro..." 
            >
        @error('his_pre_motivo') 
            <div class="text-danger small">{{ $message }}</div> 
        @enderror
    </div>
    @endif

>>>>>>> origin/backend-Elias
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
<<<<<<< HEAD
        <select name="aula_id" id="aula_id">
=======
        <select name="aula_id" id="aula_id" required>
>>>>>>> origin/backend-Elias
            <option value="">-- Selecciona el Aula --</option>
            @foreach($aulas ?? [] as $a)
                <option value="{{ $a->aula_id }}" {{ old('aula_id', $activo->aula_id ?? '') == $a->aula_id ? 'selected' : '' }}>
                    {{ $a->aula_nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="post-form">
<<<<<<< HEAD
        <label for="cate_id">Categoria del equipo <span class="text-danger">*</span></label> 
        <select name="cate_id" id="cate_id">
=======
        <label for="cate_id">Categoria <span class="text-danger">*</span></label> 
        <select name="cate_id" id="cate_id" required>
>>>>>>> origin/backend-Elias
            <option value="">-- Selecciona la categoria --</option>
            @foreach($categorias ?? [] as $cate)
                <option value="{{ $cate->cate_id }}" {{ old('cate_id', $activo->cate_id ?? '') == $cate->cate_id ? 'selected' : '' }}>
                    {{ $cate->cate_nombre }}
                </option>
            @endforeach
        </select>
    </div>

<<<<<<< HEAD
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
=======
    <div class="col-md-6">
        <label for="act_fecha_ingreso">Fecha de Ingreso <span class="text-danger">*</span></label>
        <input type="date" name="act_fecha_ingreso" value="{{ old('act_fecha_ingreso', $activo->act_fecha_ingreso ?? date('Y-m-d')) }}" class="form-control bg-light border-0 py-2 px-3 rounded-pill" required>
    </div>

    <div class="post-form-switch">
        <span class="label-switch">¿Está disponible para reservas? <span class="text-danger">*</span></span>
        <label class="switch-contenedor" for="act_reservable" >
            <input type="hidden" name="act_reservable" value="0" required>
>>>>>>> origin/backend-Elias
            <input type="checkbox" id="act_reservable" name="act_reservable" value="1"
                   {{ old('act_reservable', $activo->act_reservable ?? '1') == '1' ? 'checked' : '' }}>
            <span class="switch-slider"></span>
        </label>
    </div>

    {{-- BOTONES DE ACCIÓN --}}
<<<<<<< HEAD

    <div class="contenedor-botones">
        <x-botones.boton type="button" class="btn-siger-accion btn-rojo" onclick="window.location.href='{{ url('/inventario') }}'">
            Cancelar
        </x-botones.boton>

        <x-botones.boton type="submit" class="btn-siger-accion btn-verde">
=======
    <div class="contenedor-botones">
        <x-botones.boton type="button" class="btn-siger-accion btn btn-rojo" onclick="window.location.href='{{ url('/inventario') }}'">
            Cancelar
        </x-botones.boton>

        <x-botones.boton type="submit" class="btn-siger-accion btn btn-verde">
>>>>>>> origin/backend-Elias
            {{ $esEdicion ? 'Guardar Cambios' : 'Registrar Activo' }}
        </x-botones.boton>
    </div>
</form>