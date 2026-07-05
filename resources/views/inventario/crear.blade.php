@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 my-4">
    <div class="row">
        <div class="col-12">

            <div class="w-100 p-5 mb-5" style="background-color: var(--color-principal, #00a884); color: white; border-radius: 15px;">
                <h1 class="fw-bold display-5 m-0 mb-3">Registrar Nuevo Activo</h1>
                <p class="m-0 fs-5 opacity-75">Sincronizado con la base de datos de la institución.</p>
            </div>

            <form action="{{ route('activos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">Nombre del Activo *</label>
                        <input type="text" name="act_nombre" value="{{ old('act_nombre') }}" class="form-control bg-light border-0 py-2 px-3 rounded-pill @error('act_nombre') is-invalid @enderror" placeholder="Ej: Computador Portátil" required>
                        @error('act_nombre') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">Número de Serial *</label>
                        <input type="text" name="act_serial" value="{{ old('act_serial') }}" class="form-control bg-light border-0 py-2 px-3 rounded-pill @error('act_serial') is-invalid @enderror" placeholder="Ej: SN-123456" required>
                        @error('act_serial') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">Marca</label>
                        <input type="text" name="act_marca" value="{{ old('act_marca') }}" class="form-control bg-light border-0 py-2 px-3 rounded-pill @error('act_marca') is-invalid @enderror" placeholder="Ej: Dell, HP, Epson">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">Fecha de Ingreso *</label>
                        <input type="date" name="act_fecha_ingreso" value="{{ old('act_fecha_ingreso', date('Y-m-d')) }}" class="form-control bg-light border-0 py-2 px-3 rounded-pill" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">Estado Físico *</label>
                        <select name="act_estado_fisico" class="form-select bg-light border-0 py-2 px-3 rounded-pill" required>
                            <option value="">Seleccione estado...</option>
                            <option value="Excelente" {{ old('act_estado_fisico') == 'Excelente' ? 'selected' : '' }}>Excelente</option>
                            <option value="Bueno" {{ old('act_estado_fisico') == 'Bueno' ? 'selected' : '' }}>Bueno</option>
                            <option value="Regular" {{ old('act_estado_fisico') == 'Regular' ? 'selected' : '' }}>Regular</option>
                            <option value="Malo" {{ old('act_estado_fisico') == 'Malo' ? 'selected' : '' }}>Malo</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">¿Es reservable para préstamo? *</label>
                        <select name="act_reservable" class="form-select bg-light border-0 py-2 px-3 rounded-pill" required>
                            <option value="1" {{ old('act_reservable') == '1' ? 'selected' : '' }}>Sí, permitir reservas</option>
                            <option value="0" {{ old('act_reservable') == '0' ? 'selected' : '' }}>No, solo uso interno</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">Aula de Ubicación *</label>
                        <select name="aula_id" class="form-select bg-light border-0 py-2 px-3 rounded-pill" required>
                            <option value="">Seleccione el aula...</option>
                            @foreach($aulas as $aula)
                                <option value="{{ $aula->aula_id }}" {{ old('aula_id') == $aula->aula_id ? 'selected' : '' }}>
                                    {{ $aula->aula_nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">Categoría del Equipo *</label>
                        <select name="cate_id" class="form-select bg-light border-0 py-2 px-3 rounded-pill" required>
                            <option value="">Seleccione la categoría...</option>
                            @foreach($categorias as $cate)
                                <option value="{{ $cate->cate_id }}" {{ old('cate_id') == $cate->cate_id ? 'selected' : '' }}>
                                    {{ $cate->cate_nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label text-muted fw-bold small">Fotografía del Activo</label>
                        <input type="file" name="act_foto" class="form-control bg-light border-0 py-2 px-3 rounded-pill" accept="image/*">
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-5 mb-4">
                    <a href="{{ route('inventario.index_unificado') }}" class="btn btn-light border py-2 px-4 rounded-pill text-muted fw-bold">Cancelar</a>
                    <button type="submit" class="btn btn-success py-2 px-4 rounded-pill fw-bold" style="background-color: #00a884; border: none;">Guardar Activo</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection