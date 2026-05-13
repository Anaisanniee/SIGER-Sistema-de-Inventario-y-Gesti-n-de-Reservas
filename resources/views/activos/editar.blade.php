<!-- resources/views/activos/editar.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Activo - Siger_db</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <h2>Editar Activo: {{ $activo->act_nombre }}</h2>

    <form action="{{ route('activos.update', $activo->act_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') {{-- Esto es vital para que Laravel sepa que es una edición --}}

        <div class="mb-3">
            <label class="form-label">Nombre del Activo:</label>
            <input type="text" name="act_nombre" value="{{ old('act_nombre', $activo->act_nombre) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Serial:</label>
            <input type="text" name="act_serial" value="{{ old('act_serial', $activo->act_serial) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Aula:</label>
            <select name="aula_id" class="form-select">
                @foreach($aulas as $aula)
                    <option value="{{ $aula->aula_id }}" {{ old('aula_id', $activo->aula_id) == $aula->aula_id ? 'selected' : '' }}>
                        {{ $aula->aula_nombre }} 
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Categoría:</label>
            <select name="cate_id" class="form-select @error('cate_id') is-invalid @enderror">
                <option value="">Seleccione la categoría...</option>
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

        <div class="mb-3">
            <label class="form-label">Foto Actual:</label><br>
            @if($activo->act_foto)
                <img src="{{ asset('storage/' . $activo->act_foto) }}" width="150" class="mb-2 img-thumbnail">
            @endif
            <input type="file" name="act_foto" class="form-control">
        </div>

        <button type="submit" class="btn btn-warning">Actualizar Cambios</button>
        <a href="{{ route('activos.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</body>
</html>