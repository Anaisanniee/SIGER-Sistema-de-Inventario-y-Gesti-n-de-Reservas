<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Activos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <h2>Registrar Nuevo Activo</h2>

    @if(session('mensaje'))
        <div class="alert alert-success">{{ session('mensaje') }}</div>
    @endif

    <!-- IMPORTANTE: enctype="multipart/form-data" permite subir archivos -->
    <form action="{{ route('activos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Nombre del Activo:</label>
            <input type="text" name="act_nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Serial:</label>
            <input type="text" name="act_serial" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Aula (ID):</label>
            <input type="number" name="aula_id" class="form-control" placeholder="Ej: 1" required>
        </div>

        <div class="mb-3">
            <label>Categoría (ID):</label>
            <input type="number" name="cate_id" class="form-control" placeholder="Ej: 1" required>
        </div>

        <div class="mb-3">
            <label>Estado Físico:</label>
            <select name="act_estado_fisico" class="form-control" required>
                <option value="Nuevo">Nuevo</option>
                <option value="Bueno">Bueno</option>
                <option value="Regular">Regular</option>
                <option value="Malo">Malo</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Foto del Producto:</label>
            <input type="file" name="act_foto" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Guardar Activo</button>
    </form>

</body>
</html>