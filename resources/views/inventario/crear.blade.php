@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 my-4">
    <div class="row">
        <div class="col-12">

            <div class="w-100 p-5 mb-5" style="background-color: var(--color-principal, #00a884); color: white; border-radius: 15px;">
                <h1 class="fw-bold display-5 m-0 mb-3">Registrar Nuevo Activo</h1>
                <p class="m-0 fs-5 opacity-75">Sincronizado con la base de datos de la institución bajo los parámetros del sistema.</p>
            </div>

            <form action="#" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">Nombre del Activo *</label>
                        <input type="text" name="ACT_NOMBRE" class="form-control bg-light border-0 py-2 px-3 rounded-pill" placeholder="Ej: Computador Portátil, Proyector" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">Número de Serial *</label>
                        <input type="text" name="ACT_SERIAL" class="form-control bg-light border-0 py-2 px-3 rounded-pill" placeholder="Ej: SN-12345678" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">Marca</label>
                        <input type="text" name="ACT_MARCA" class="form-control bg-light border-0 py-2 px-3 rounded-pill" placeholder="Ej: Dell, HP, Epson">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">Fecha de Ingreso *</label>
                        <input type="date" name="ACT_FECHA_INGRESO" class="form-control bg-light border-0 py-2 px-3 rounded-pill" required value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">Estado Físico *</label>
                        <select name="ACT_ESTADO_FISICO" class="form-select bg-light border-0 py-2 px-3 rounded-pill" required>
                            <option value="" selected disabled>Seleccionar estado...</option>
                            <option value="Excelente">Excelente</option>
                            <option value="Bueno">Bueno</option>
                            <option value="Regular">Regular</option>
                            <option value="Malo">Malo</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">¿Es reservable para préstamo? *</label>
                        <select name="ACT_RESERVABLE" class="form-select bg-light border-0 py-2 px-3 rounded-pill" required>
                            <option value="1" selected>Sí, permitir reservas</option>
                            <option value="0">No, solo uso interno fijo</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">Aula de Ubicación *</label>
                        <select name="AULA_ID" class="form-select bg-light border-0 py-2 px-3 rounded-pill" required>
                            <option value="" selected disabled>Asignar a un aula...</option>
                            <option value="1">Aula 101 --- Bloque A</option>
                            <option value="2">Aula 102 --- Bloque B</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">Categoría del Equipo *</label>
                        <select name="CATE_ID" class="form-select bg-light border-0 py-2 px-3 rounded-pill" required>
                            <option value="" selected disabled>Seleccionar categoría...</option>
                            <option value="1">Tecnología / Cómputo</option>
                            <option value="2">Audiovisuales</option>
                            <option value="3">Mobiliario</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label text-muted fw-bold small">Fotografía del Activo</label>
                        <input type="file" name="ACT_FOTO" class="form-control bg-light border-0 py-2 px-3" style="border-radius: 20px;" accept="image/*">
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-5 mb-4">
                    <a href="{{ url('/prestamos/equipos') }}" class="btn btn-light border py-2 px-4 rounded-pill text-muted fw-bold" style="min-width: 130px; background-color: #f8f9fa;">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-success py-2 px-4 rounded-pill fw-bold" style="background-color: var(--color-principal, #00a884); border: none; min-width: 150px;">
                        Guardar Activo
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection