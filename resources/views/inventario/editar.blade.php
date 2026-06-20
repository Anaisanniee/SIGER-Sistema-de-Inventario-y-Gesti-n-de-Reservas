@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-md-5 bg-white">
    
    <div class="p-4 mb-4 text-white rounded-4" style="background-color: #00bfa5;">
        <h1 class="fw-bold mb-2" style="font-size: 2.2rem;">Editar Activo</h1>
        <p class="mb-0 opacity-90" style="font-size: 1rem;">
            Actualiza los parámetros del sistema para el activo seleccionado en la institución.
        </p>
    </div>

    <form action="#" method="POST" class="px-2">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-12">
                <label for="nombre" class="form-label fw-bold text-secondary mb-1" style="font-size: 0.9rem;">Nombre del Activo *</label>
                <input type="text" class="form-control border-0 bg-light py-2 px-3 rounded-pill" id="nombre" placeholder="Ej: Computador Portátil, Proyector">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="serial" class="form-label fw-bold text-secondary mb-1" style="font-size: 0.9rem;">Número de Serial *</label>
                <input type="text" class="form-control border-0 bg-light py-2 px-3 rounded-pill" id="serial" placeholder="Ej: SN-12345678">
            </div>
            <div class="col-md-6">
                <label for="marca" class="form-label fw-bold text-secondary mb-1" style="font-size: 0.9rem;">Marca</label>
                <input type="text" class="form-control border-0 bg-light py-2 px-3 rounded-pill" id="marca" placeholder="Ej: Dell, HP, Epson">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="fecha_ingreso" class="form-label fw-bold text-secondary mb-1" style="font-size: 0.9rem;">Fecha de Ingreso *</label>
                <input type="date" class="form-control border-0 bg-light py-2 px-3 rounded-pill" id="fecha_ingreso">
            </div>
            <div class="col-md-6">
                <label for="estado_fisico" class="form-label fw-bold text-secondary mb-1" style="font-size: 0.9rem;">Estado Físico *</label>
                <select class="form-select border-0 bg-light py-2 px-3 rounded-pill text-secondary" id="estado_fisico">
                    <option selected disabled>Seleccionar estado...</option>
                    <option value="Bueno">Bueno</option>
                    <option value="Regular">Regular</option>
                    <option value="Malo">Malo</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="is_reservable" class="form-label fw-bold text-secondary mb-1" style="font-size: 0.9rem;">¿Es reservable para préstamo? *</label>
                <select class="form-select border-0 bg-light py-2 px-3 rounded-pill text-secondary" id="is_reservable">
                    <option selected disabled>Seleccionar opción...</option>
                    <option value="si">Sí, permitir reservas</option>
                    <option value="no">No reservable</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="aula" class="form-label fw-bold text-secondary mb-1" style="font-size: 0.9rem;">Aula de Ubicación *</label>
                <select class="form-select border-0 bg-light py-2 px-3 rounded-pill text-secondary" id="aula">
                    <option selected disabled>Asignar a un aula...</option>
                    <option value="1">Aula 101</option>
                    <option value="2">Lab Sistemas 102</option>
                    <option value="3">Auditorio Principal</option>
                </select>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label for="categoria" class="form-label fw-bold text-secondary mb-1" style="font-size: 0.9rem;">Categoría del Equipo *</label>
                <select class="form-select border-0 bg-light py-2 px-3 rounded-pill text-secondary" id="categoria">
                    <option selected disabled>Seleccionar categoría...</option>
                    <option value="Cómputo">Cómputo</option>
                    <option value="Mobiliario">Mobiliario</option>
                    <option value="Audiovisual">Audiovisual</option>
                </select>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-12">
                <label for="foto" class="form-label fw-bold text-secondary mb-1" style="font-size: 0.9rem;">Fotografía del Activo</label>
                <input type="file" class="form-control border-0 bg-light py-2 px-3 rounded-pill text-secondary" id="foto">
            </div>
        </div>

        <div class="d-flex justify-content-end gap-3 pt-3">
            <a href="{{ route('inventario.prestamos') }}" class="btn btn-light border-0 fw-semibold py-2 px-4 rounded-pill text-secondary" style="background-color: #f1f3f4; width: 150px; font-size: 0.95rem;">
                Cancelar
            </a>
            <button type="button" class="btn text-white fw-semibold py-2 px-4 rounded-pill" style="background-color: #00bfa5; border: none; width: 170px; font-size: 0.95rem;">
                Guardar Activo
            </button>
        </div>

    </form>
</div>
@endsection