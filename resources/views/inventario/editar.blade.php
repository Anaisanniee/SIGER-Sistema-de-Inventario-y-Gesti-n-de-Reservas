@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-md-5 bg-white">
    
    <div class="p-4 mb-4 text-white rounded-4" style="background-color: #00bfa5;">
        <h1 class="fw-bold mb-2" style="font-size: 2.2rem;">Editar Activo</h1>
        <p class="mb-0 opacity-90" style="font-size: 1rem;">
            Actualiza los parámetros del sistema para el activo seleccionado en la institución.
        </p>
    </div>

    <form action="{{ route('activos.update', $activo->act_id) }}" method="POST" enctype="multipart/form-data" class="mt-3">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-12">
                <label for="nombre" class="form-label fw-bold text-secondary mb-1" style="font-size: 0.9rem;">Nombre del Activo *</label>
                <input type="text" name="act_nombre" value="{{ old('act_nombre', $activo->act_nombre) }}" class="form-control border-0 bg-light py-2 px-3 rounded-pill @error('act_nombre') is-invalid @enderror">
                @error('act_nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="serial" class="form-label fw-bold text-secondary mb-1" style="font-size: 0.9rem;">Número de Serial *</label>
                <input type="text" name="act_serial" value="{{ old('act_serial', $activo->act_serial) }}" class="form-control border-0 bg-light py-2 px-3 rounded-pill @error('act_serial') is-invalid @enderror">
                @error('act_serial')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="marca" class="form-label fw-bold text-secondary mb-1" style="font-size: 0.9rem;">Marca</label>
                <input type="text" name="act_marca" value="{{ old('act_marca', $activo->act_marca) }}" class="form-control border-0 bg-light py-2 px-3 rounded-pill @error('act_marca') is-invalid @enderror">
                @error('act_marca')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="fecha_ingreso" class="form-label fw-bold text-secondary mb-1" style="font-size: 0.9rem;">Fecha de Ingreso *</label>
                <input type="date" name="act_fecha_ingreso" value="{{ old('act_fecha_ingreso', $activo->act_fecha_ingreso) }}" class="form-control border-0 bg-light py-2 px-3 rounded-pill @error('act_fecha_ingreso') is-invalid @enderror">
                @error('act_fecha_ingreso')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="estado_fisico" class="form-label fw-bold text-secondary mb-1" style="font-size: 0.9rem;">Estado Físico *</label>
                <select name="act_estado_fisico" class="form-select border-0 bg-light py-2 px-3 rounded-pill text-secondary @error('act_estado_fisico') is-invalid @enderror">
                    <option selected disabled>Seleccionar estado...</option>
                    <option value="Excelente" {{ old('act_estado_fisico', $activo->act_estado_fisico) == 'Excelente' ? 'selected' : '' }}>Excelente</option>
                    <option value="Bueno" {{ old('act_estado_fisico', $activo->act_estado_fisico) == 'Bueno' ? 'selected' : '' }}>Bueno</option>
                    <option value="Regular" {{ old('act_estado_fisico', $activo->act_estado_fisico) == 'Regular' ? 'selected' : '' }}>Regular</option>
                    <option value="Malo" {{ old('act_estado_fisico', $activo->act_estado_fisico) == 'Malo' ? 'selected' : '' }}>Malo</option>
                </select>
                @error('act_estado_fisico')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="is_reservable" class="form-label fw-bold text-secondary mb-1" style="font-size: 0.9rem;">¿Es reservable para préstamo? *</label>
                <select name="act_reservable" class="form-select border-0 bg-light py-2 px-3 rounded-pill text-secondary @error('act_reservable') is-invalid @enderror">
                    <option selected disabled>Seleccionar opción...</option>
                    <option value="1" {{ old('act_reservable', $activo->act_reservable) == '1' ? 'selected' : '' }}>Sí, se puede reservar</option>
                    <option value="0" {{ old('act_reservable', $activo->act_reservable) == '0' ? 'selected' : '' }}>No reservable</option>
                </select>
                @error('act_reservable')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="aula" class="form-label fw-bold text-secondary mb-1" style="font-size: 0.9rem;">Aula de Ubicación *</label>
                <select name="aula_id" class="form-select border-0 bg-light py-2 px-3 rounded-pill text-secondary @error('aula_id') is-invalid @enderror">
                    <option selected disabled>Seleccionar opción...</option>
                    @foreach($aulas as $aula)
                        <option value="{{ $aula->aula_id }}" {{ old('aula_id', $activo->aula_id) == $aula->aula_id ? 'selected' : '' }}>
                            {{ $aula->aula_nombre }} 
                        </option>
                    @endforeach
                </select>
                @error('aula_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label for="categoria" class="form-label fw-bold text-secondary mb-1" style="font-size: 0.9rem;">Categoría del Equipo *</label>
                <select name="cate_id" class="form-select border-0 bg-light py-2 px-3 rounded-pill text-secondary @error('cate_id') is-invalid @enderror">
                    <option selected disabled>Seleccione la categoría...</option>
                    @foreach($categorias as $cate)
                        <option value="{{ $cate->cate_id }}" {{ old('cate_id', $activo->cate_id) == $cate->cate_id ? 'selected' : '' }}>
                            {{ $cate->cate_nombre }}
                        </option>
                    @endforeach
                </select>
                @error('cate_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-12">
                <label for="foto" class="form-label fw-bold text-secondary mb-1" style="font-size: 0.9rem;">Fotografía del Activo</label>
                @if($activo->act_foto)
                    <div class="mb-2">
                        <small class="text-muted d-block">Foto actual:</small>
                        <img src="{{ asset('storage/' . $activo->act_foto) }}" width="150" class="img-thumbnail">
                    </div>
                @endif
                <input type="file" name="act_foto" class="form-control @error('act_foto') is-invalid @enderror" accept="image/*">
                <small class="form-text text-muted">Selecciona un archivo solo si deseas reemplazar la foto actual.</small>
                @error('act_foto')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex justify-content-end gap-3 pt-3">
            <a href="{{ route('inventario.index_unificado') }}" class="btn btn-light border-0 fw-semibold py-2 px-4 rounded-pill text-secondary" style="background-color: #f1f3f4; width: 150px; font-size: 0.95rem;">
                Cancelar
            </a>
            <button type="submit" class="btn text-white fw-semibold py-2 px-4 rounded-pill" style="background-color: #00bfa5; border: none; width: 170px; font-size: 0.95rem;">
                Guardar Activo
            </button>
        </div>

    </form>
</div>
@endsection