@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Registrar Nueva Aula</h3>
        </div>
        <div class="card-body">
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('aulas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Nombre del Aula</label>
                    <input type="text" name="aula_nombre" class="form-control" required maxlength="25" value="{{ old('aula_nombre') }}">
                    <small class="text-muted">Máximo 25 caracteres.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Foto del Aula</label>
                    <input type="file" name="aula_foto" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label class="form-label">Capacidad</label>
                    <input type="number" name="aula_capacidad" class="form-control" required min="1" value="{{ old('aula_capacidad') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Estado</label>
                    <select name="aula_estado" class="form-control" required>
                        <option value="">Seleccione el estado</option>
                        <option value="Disponible" {{ old('aula_estado') == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="Ocupado" {{ old('aula_estado') == 'Ocupado' ? 'selected' : '' }}>Ocupado</option>
                        <option value="En Mantenimiento" {{ old('aula_estado') == 'En Mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipo de Aula</label>
                    <select name="tip_aula_id" class="form-control" required>
                        <option value="">Seleccione un tipo</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->tip_aula_id }}" {{ old('tip_aula_id') == $tipo->tip_aula_id ? 'selected' : '' }}>
                                {{ $tipo->tip_aula_nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">¿Es reservable?</label>
                    <select name="aula_reservable" class="form-control">
                        <option value="1" {{ old('aula_reservable') == '1' ? 'selected' : '' }}>Sí</option>
                        <option value="0" {{ old('aula_reservable') == '0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">Guardar Aula</button>
                    <a href="{{ route('inventario.index_unificado') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection