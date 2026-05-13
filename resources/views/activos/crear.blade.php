<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Activos - Siger_db</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <h2>Registrar Nuevo Activo</h2>

    @if(session('mensaje'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('mensaje') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('activos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nombre del Activo:</label>
            <input type="text" name="act_nombre" value="{{ old('act_nombre') }}" 
                class="form-control @error('act_nombre') is-invalid @enderror">
            @error('act_nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Serial:</label>
            <input type="text" name="act_serial" value="{{ old('act_serial') }}" 
                class="form-control @error('act_serial') is-invalid @enderror">
            @error('act_serial')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- CAMPO AULA (Añadido) -->
        <div class="mb-3">
            <label class="form-label">Aula:</label>
                <select name="aula_id" class="form-select @error('aula_id') is-invalid @enderror">
                    <option value="">Seleccione el aula...</option>
                    @foreach($aulas as $aula)
                        <option value="{{ $aula->aula_id }}" {{ old('aula_id') == $aula->aula_id ? 'selected' : '' }}>
                            {{ $aula->aula_nombre }} 
                        </option>
                    @endforeach
                </select>
            @error('aula_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

<!-- Select para Categorías -->
        <div class="mb-3">
            <label class="form-label">Categoría:</label>
            <select name="cate_id" class="form-select @error('cate_id') is-invalid @enderror">
                <option value="">Seleccione la categoría...</option>
                @foreach($categorias as $cate)
                    <option value="{{ $cate->cate_id }}" {{ old('cate_id') == $cate->cate_id ? 'selected' : '' }}>
                        {{ $cate->cate_nombre }}
                    </option>
                @endforeach
            </select>
            @error('cate_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Estado Físico:</label>
            <select name="act_estado_fisico" class="form-select @error('act_estado_fisico') is-invalid @enderror">
                <option value="">Seleccione...</option>
                <option value="Nuevo" {{ old('act_estado_fisico') == 'Nuevo' ? 'selected' : '' }}>Nuevo</option>
                <option value="Bueno" {{ old('act_estado_fisico') == 'Bueno' ? 'selected' : '' }}>Bueno</option>
                <option value="Regular" {{ old('act_estado_fisico') == 'Regular' ? 'selected' : '' }}>Regular</option>
                <option value="Malo" {{ old('act_estado_fisico') == 'Malo' ? 'selected' : '' }}>Malo</option>
            </select>
            @error('act_estado_fisico')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- CAMPO FOTO (Añadido) -->
        <div class="mb-3">
            <label class="form-label">Foto del Activo:</label>
            <input type="file" name="act_foto" 
                class="form-control @error('act_foto') is-invalid @enderror">
            @error('act_foto')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Guardar Activo</button>
            <a href="{{ route('activos.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>