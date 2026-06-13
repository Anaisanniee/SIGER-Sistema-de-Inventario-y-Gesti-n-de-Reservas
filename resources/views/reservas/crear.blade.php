{{-- resources/views/reservas/crear.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            @include('componentes.banner-bienvenida', [
                'titulo' => 'Registrar Nueva Aula',
                'descripcion' => 'Añade y configura los espacios físicos de la institución bajo los parámetros del sistema.'
            ])
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <h4 class="fw-bold mb-4 text-dark d-flex align-items-center gap-2">
                <span class="fs-4">🏫</span> Especificaciones del Aula
            </h4>
            
            <form action="{{ route('aulas.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="AULA_NOMBRE" class="form-label fw-bold text-muted small">Nombre del Aula</label>
                        <input type="text" name="AULA_NOMBRE" id="AULA_NOMBRE" class="form-control rounded-3 py-2 bg-light border-0 shadow-sm" placeholder="Ej: Aula 102, Laboratorio B" max="25" required>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="TIP_AULA_ID" class="form-label fw-bold text-muted small">Tipo de Aula</label>
                        <select name="TIP_AULA_ID" id="TIP_AULA_ID" class="form-select rounded-3 py-2 bg-light border-0 shadow-sm" required>
                            <option value="">Selecciona el tipo</option>
                            <option value="1">Aula de Clase Común</option>
                            <option value="2">Laboratorio de Sistemas</option>
                            <option value="3">Auditorio / Sala de Eventos</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="AULA_CAPACIDAD" class="form-label fw-bold text-muted small">Capacidad Máxima (Personas)</label>
                        <input type="number" name="AULA_CAPACIDAD" id="AULA_CAPACIDAD" class="form-control rounded-3 py-2 bg-light border-0 shadow-sm" placeholder="Ej: 35" min="1" required>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="AULA_ESTADO" class="form-label fw-bold text-muted small">Estado del Aula</label>
                        <select name="AULA_ESTADO" id="AULA_ESTADO" class="form-select rounded-3 py-2 bg-light border-0 shadow-sm" required>
                            <option value="Disponible">Disponible</option>
                            <option value="Mantenimiento">En Mantenimiento</option>
                            <option value="Inactiva">Inactiva</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="AULA_RESERVABLE" class="form-label fw-bold text-muted small">¿Es reservable por los docentes?</label>
                        <select name="AULA_RESERVABLE" id="AULA_RESERVABLE" class="form-select rounded-3 py-2 bg-light border-0 shadow-sm" required>
                            <option value="1">Sí, permitir reservas</option>
                            <option value="0">No, uso exclusivo administrativo</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4">
                    <button type="button" onclick="window.location.href='{{ url('/reservas') }}'" class="btn btn-light border rounded-pill py-2 fw-bold text-secondary" style="width: 210px !important; box-sizing: border-box !important;">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-success rounded-pill py-2 fw-bold" style="background-color: #00b18d; border: none; width: 210px !important; box-sizing: border-box !important;">
                        Guardar Espacio
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection