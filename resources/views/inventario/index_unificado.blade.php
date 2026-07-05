@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <ul class="nav nav-tabs mb-4" id="inventarioTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-activos">Dispositivos</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-aulas">Aulas</button>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="tab-activos">
            <div class="d-flex justify-content-between align-items-center mb-4 row-md-10 offset-md-1 px-3">
                <h2 class="mb-0">Inventario de Dispositivos</h2>
                <a href="{{ route('activos.eliminados') }}" class="btn btn-secondary btn-sm shadow-sm">
                    <i class="fas fa-trash-alt me-1"></i> Ver Papelera
                </a>
            </div>
            
            <div class="row mb-2">
                <div class="col-md-10 offset-md-1">
                    <form action="{{ route('inventario.index_unificado') }}" method="GET" class="row g-2 shadow-sm p-3 bg-white rounded border">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" name="buscar" class="form-control border-start-0" placeholder="Nombre o serial..." value="{{ request('buscar') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select name="categoria" class="form-select">
                                <option value="">Todas las categorías</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->cate_id }}" {{ request('categoria') == $cat->cate_id ? 'selected' : '' }}>{{ $cat->cate_nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                            @if(request('buscar') || request('categoria'))
                                <a href="{{ route('inventario.index_unificado') }}" class="btn btn-outline-secondary w-100">Limpiar</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-10 offset-md-1">
                    <p class="text-muted small">
                        @if(request('buscar') || request('categoria'))
                            Se encontraron <strong>{{ $totalActivos }}</strong> resultados.
                        @else
                            Total de activos: <strong>{{ $totalActivos }}</strong>
                        @endif
                    </p>
                </div>
            </div>

            <div class="row">
                @forelse($activos as $activo)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div style="height: 180px; overflow: hidden; position: relative;">
                                @if($activo->act_foto)
                                    <img src="{{ asset('storage/' . $activo->act_foto) }}" class="card-img-top" style="object-fit: cover; height: 100%; width: 100%;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 100%;"><span class="text-muted small">Sin imagen</span></div>
                                @endif
                                <span class="position-absolute top-0 end-0 badge bg-dark m-2 opacity-75">{{ $activo->categoria->cate_nombre ?? 'S/C' }}</span>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title h6 text-truncate mb-1">{{ $activo->act_nombre }}</h5>
                                <p class="card-text text-muted mb-3" style="font-size: 0.8rem;"><i class="fas fa-barcode me-1"></i> {{ $activo->act_serial }}</p>
                                <div class="mt-auto">
                                    <button type="button" class="btn btn-primary btn-sm w-100 mb-2" data-bs-toggle="modal" data-bs-target="#ficha{{ $activo->act_id }}">
                                        <i class="fas fa-eye"></i> Ver Ficha
                                    </button>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('activos.edit', $activo->act_id) }}" class="btn btn-warning btn-sm flex-grow-1"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('activos.destroy', $activo->act_id) }}" method="POST" id="delete-form-{{ $activo->act_id }}" class="flex-grow-1">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm w-100" onclick="confirmarEliminacion({{ $activo->act_id }})"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5"><i class="fas fa-box-open fa-3x text-muted mb-3"></i><p class="text-muted">No se encontraron activos.</p></div>
                @endforelse
            </div>
        </div>

        <div class="tab-pane fade" id="tab-aulas">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Gestión de Aulas</h1>
                <div>
                    <a href="{{ route('aulas.trashed') }}" class="btn btn-outline-danger btn-sm">Ver Papelera</a>
                    <a href="{{ route('aulas.create') }}" class="btn btn-primary btn-sm">Nueva Aula</a>
                </div>
            </div>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="row">
                @forelse($aulas as $aula)
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                @if($activo->act_foto)
                                    <img src="{{ asset('storage/' . $aula->aula_foto) }}" class="card-img-top" style="object-fit: cover; height: 100%; width: 100%;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 100%;"><span class="text-muted small">Sin imagen</span></div>
                                @endif
                                <h5 class="card-title">{{ $aula->aula_nombre }}</h5>
                                <p class="card-text">
                                    Capacidad: {{ $aula->aula_capacidad }} <br>
                                    Estado: {{ $aula->aula_estado }} <br>
                                    Reservable: {{ $aula->aula_reservable ? 'Sí' : 'No' }}
                                </p>
                                <a href="{{ route('aulas.show', $aula->aula_id) }}" class="btn btn-info btn-sm">Ver Ficha</a>
                                <button class="btn btn-danger btn-sm" onclick="confirmarBaja({{ $aula->aula_id }})">Dar de baja</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center mt-5"><div class="alert alert-info">No hay aulas registradas.</div></div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<form id="form-baja" method="POST" style="display: none;">
    @csrf @method('DELETE')
    <input type="hidden" name="aula_motivo_baja" id="input-motivo">
</form>

@foreach($activos as $activo)
    <div class="modal fade" id="ficha{{ $activo->act_id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ficha Técnica</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        @if($activo->act_foto)
                            <img src="{{ asset('storage/' . $activo->act_foto) }}" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                        @endif
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Nombre:</strong> <span class="text-dark fw-bold">{{ $activo->act_nombre }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Serial:</strong> <span class="text-muted">{{ $activo->act_serial }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Marca:</strong> <span class="text-dark">{{ $activo->act_marca ?? 'Sin registrar' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Categoría:</strong> <span class="badge bg-info text-dark px-3 py-2 rounded-pill fw-bold">{{ $activo->categoria->cate_nombre ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Aula:</strong> <span class="text-dark">{{ $activo->aula->aula_nombre ?? 'No asignada' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Estado:</strong> <span class="text-dark">{{ $activo->act_estado_fisico }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>¿Es Reservable?</strong> 
                            @if($activo->act_reservable == 1)
                                <span class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i>Sí, se puede reservar</span>
                            @else
                                <span class="text-danger fw-bold"><i class="fas fa-times-circle me-1"></i>No reservable</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Fecha de Ingreso:</strong> 
                            <span class="text-dark">
                                {{ $activo->act_fecha_ingreso ? \Carbon\Carbon::parse($activo->act_fecha_ingreso)->format('d/m/Y') : 'Sin fecha' }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection

@section('scripts')
<script>
    // 1. Función para eliminar dispositivos (tu código actual)
    function confirmarEliminacion(id) {
        Swal.fire({
            title: '¿Eliminar este dispositivo?',
            text: "Por favor, escribe el motivo de la baja:",
            icon: 'warning',
            input: 'text',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Sí, borrar'
        }).then((result) => {
            if (result.isConfirmed) {
                let formulario = document.getElementById('delete-form-' + id);
                let inputMotivo = document.createElement('input');
                inputMotivo.type = 'hidden'; 
                inputMotivo.name = 'act_motivo_baja'; 
                inputMotivo.value = result.value;
                formulario.appendChild(inputMotivo);
                formulario.submit();
            }
        })
    }

    // 2. Función para dar de baja AULAS (lo que faltaba)
    function confirmarBaja(id) {
        Swal.fire({
            title: 'Motivo de la baja del aula',
            input: 'text',
            showCancelButton: true,
            confirmButtonText: 'Confirmar',
            inputValidator: (value) => { if (!value) return 'Necesitas escribir un motivo'; }
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.getElementById('form-baja');
                form.action = '/aulas/' + id; // Asegura que esta ruta exista en tu route:list
                document.getElementById('input-motivo').value = result.value;
                form.submit();
            }
        });
    }
</script>
@endsection