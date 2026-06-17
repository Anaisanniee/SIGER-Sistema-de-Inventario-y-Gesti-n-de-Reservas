@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Editar Aula: {{ $aula->aula_nombre }}</h3>
        </div>
        <div class="card-body">
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('aulas.update', $aula->aula_id) }}" method="POST">
                @csrf
                @method('PUT') 
                
                <div class="mb-3">
                    <label>Nombre del Aula</label>
                    <input type="text" name="aula_nombre" class="form-control" value="{{ old('aula_nombre', $aula->aula_nombre) }}" required maxlength="25">
                </div>

                <div class="mb-3">
                    <label>Capacidad</label>
                    <input type="number" name="aula_capacidad" class="form-control" value="{{ old('aula_capacidad', $aula->aula_capacidad) }}" required min="1">
                </div>

                <div class="mb-3">
                    <label>Estado</label>
                    <select name="aula_estado" class="form-control" required>
                        <option value="Disponible" {{ old('aula_estado', $aula->aula_estado) == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="Ocupado" {{ old('aula_estado', $aula->aula_estado) == 'Ocupado' ? 'selected' : '' }}>Ocupado</option>
                        <option value="En Mantenimiento" {{ old('aula_estado', $aula->aula_estado) == 'En Mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tipo de Aula</label>
                    <select name="tip_aula_id" class="form-control" required>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->tip_aula_id }}" {{ old('tip_aula_id', $aula->tip_aula_id) == $tipo->tip_aula_id ? 'selected' : '' }}>
                                {{ $tipo->tip_aula_nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>¿Es reservable?</label>
                    <select name="aula_reservable" class="form-control">
                        <option value="1" {{ old('aula_reservable', $aula->aula_reservable) == '1' ? 'selected' : '' }}>Sí</option>
                        <option value="0" {{ old('aula_reservable', $aula->aula_reservable) == '0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Actualizar Aula</button>
                <a href="{{ route('aulas.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection