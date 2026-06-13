<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <form action="{{ route('activos.store') }}" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf

        <div class="mb-3">
            <label class="form-label font-weight-bold">Nombre del Activo:</label>
            <input type="text" name="act_nombre" value="{{ old('act_nombre') }}" 
                class="form-control @error('act_nombre') is-invalid @enderror" placeholder="Ej. Computador Portátil" required>
            @error('act_nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Serial:</label>
                <input type="text" name="act_serial" value="{{ old('act_serial') }}" 
                    class="form-control @error('act_serial') is-invalid @enderror" placeholder="Ej. SN-123456" required>
                @error('act_serial')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Marca:</label>
                <input type="text" name="act_marca" value="{{ old('act_marca') }}" 
                    class="form-control @error('act_marca') is-invalid @enderror" placeholder="Ej. HP, Lenovo, Dell">
                @error('act_marca')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Aula:</label>
                <select name="aula_id" class="form-select @error('aula_id') is-invalid @enderror" required>
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

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Categoría:</label>
                <select name="cate_id" class="form-select @error('cate_id') is-invalid @enderror" required>
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
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold">Estado Físico:</label>
                <select name="act_estado_fisico" class="form-select @error('act_estado_fisico') is-invalid @enderror" required>
                    <option value="">Seleccione...</option>
                    <option value="Excelente" {{ old('act_estado_fisico') == 'Excelente' ? 'selected' : '' }}>Excelente</option>
                    <option value="Bueno" {{ old('act_estado_fisico') == 'Bueno' ? 'selected' : '' }}>Bueno</option>
                    <option value="Regular" {{ old('act_estado_fisico') == 'Regular' ? 'selected' : '' }}>Regular</option>
                    <option value="Malo" {{ old('act_estado_fisico') == 'Malo' ? 'selected' : '' }}>Malo</option>
                </select>
                @error('act_estado_fisico')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold">¿Es Reservable?</label>
                <select name="act_reservable" class="form-select @error('act_reservable') is-invalid @enderror" required>
                    <option value="">Seleccione...</option>
                    <option value="1" {{ old('act_reservable') == '1' ? 'selected' : '' }}>Sí, se puede reservar</option>
                    <option value="0" {{ old('act_reservable') == '0' ? 'selected' : '' }}>No reservable</option>
                </select>
                @error('act_reservable')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold">Fecha de Ingreso:</label>
                <input type="date" name="act_fecha_ingreso" value="{{ old('act_fecha_ingreso', date('Y-m-d')) }}" 
                    class="form-control @error('act_fecha_ingreso') is-invalid @enderror" required>
                @error('act_fecha_ingreso')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label font-weight-bold">Foto del Activo:</label>
            <input type="file" name="act_foto" class="form-control @error('act_foto') is-invalid @enderror" accept="image/*">
            @error('act_foto')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mt-4 mb-5">
            <button type="submit" class="btn btn-primary px-4">Guardar Activo</button>
            <a href="{{ route('activos.index') }}" class="btn btn-secondary px-4">Cancelar</a>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>