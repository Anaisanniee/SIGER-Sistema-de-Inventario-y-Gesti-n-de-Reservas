<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Activo - Siger_db</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <div class="card shadow-sm p-4 mb-5 bg-white rounded">
        <h2>Editar Activo: {{ $activo->act_nombre }}</h2>
        <hr>

        @if(session('mensaje'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('mensaje') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('activos.update', $activo->act_id) }}" method="POST" enctype="multipart/form-data" class="mt-3">
            @csrf
            @method('PUT') {{-- Esto es vital para que Laravel sepa que es una edición --}}

            <div class="mb-3">
                <label class="form-label fw-bold">Nombre del Activo:</label>
                <input type="text" name="act_nombre" value="{{ old('act_nombre', $activo->act_nombre) }}" 
                    class="form-control @error('act_nombre') is-invalid @enderror" required>
                @error('act_nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Serial:</label>
                    <input type="text" name="act_serial" value="{{ old('act_serial', $activo->act_serial) }}" 
                        class="form-control @error('act_serial') is-invalid @enderror" required>
                    @error('act_serial')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Marca:</label>
                    <input type="text" name="act_marca" value="{{ old('act_marca', $activo->act_marca) }}" 
                        class="form-control @error('act_marca') is-invalid @enderror">
                    @error('act_marca')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Aula:</label>
                    <select name="aula_id" class="form-select @error('aula_id') is-invalid @enderror" required>
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

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Categoría:</label>
                    <select name="cate_id" class="form-select @error('cate_id') is-invalid @enderror" required>
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
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Estado Físico:</label>
                    <select name="act_estado_fisico" class="form-select @error('act_estado_fisico') is-invalid @enderror" required>
                        <option value="">Seleccione...</option>
                        <option value="Excelente" {{ old('act_estado_fisico', $activo->act_estado_fisico) == 'Excelente' ? 'selected' : '' }}>Excelente</option>
                        <option value="Bueno" {{ old('act_estado_fisico', $activo->act_estado_fisico) == 'Bueno' ? 'selected' : '' }}>Bueno</option>
                        <option value="Regular" {{ old('act_estado_fisico', $activo->act_estado_fisico) == 'Regular' ? 'selected' : '' }}>Regular</option>
                        <option value="Malo" {{ old('act_estado_fisico', $activo->act_estado_fisico) == 'Malo' ? 'selected' : '' }}>Malo</option>
                    </select>
                    @error('act_estado_fisico')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">¿Es Reservable?</label>
                    <select name="act_reservable" class="form-select @error('act_reservable') is-invalid @enderror" required>
                        <option value="">Seleccione...</option>
                        <option value="1" {{ old('act_reservable', $activo->act_reservable) == '1' ? 'selected' : '' }}>Sí, se puede reservar</option>
                        <option value="0" {{ old('act_reservable', $activo->act_reservable) == '0' ? 'selected' : '' }}>No reservable</option>
                    </select>
                    @error('act_reservable')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Fecha de Ingreso:</label>
                    <input type="date" name="act_fecha_ingreso" value="{{ old('act_fecha_ingreso', $activo->act_fecha_ingreso) }}" 
                        class="form-control @error('act_fecha_ingreso') is-invalid @enderror" required>
                    @error('act_fecha_ingreso')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Foto del Activo:</label><br>
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

            <div class="mt-4">
                <button type="submit" class="btn btn-warning px-4 fw-bold">Actualizar Cambios</button>
                <a href="{{ route('activos.index') }}" class="btn btn-secondary px-4">Volver</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>