<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4 row-md-10 offset-md-1 px-3">
        <h2 class="mb-0">Inventario de Dispositivos</h2>
        <a href="{{ route('activos.eliminados') }}" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-trash-alt me-1"></i> Ver Papelera
        </a>
    </div>
    
    <div class="row mb-2">
        <div class="col-md-10 offset-md-1">
            <form action="{{ route('activos.index') }}" method="GET" class="row g-2 shadow-sm p-3 bg-white rounded border">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="buscar" class="form-control border-start-0" 
                               placeholder="Nombre o serial..." value="{{ request('buscar') }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <select name="categoria" class="form-select">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->cate_id }}" {{ request('categoria') == $cat->cate_id ? 'selected' : '' }}>
                                {{ $cat->cate_nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        Filtrar
                    </button>
                    @if(request('buscar') || request('categoria'))
                        <a href="{{ route('activos.index') }}" class="btn btn-outline-secondary w-100">
                            Limpiar
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-10 offset-md-1">
            <p class="text-muted small">
                @if(request('buscar') || request('categoria'))
                    Se encontraron <strong>{{ $total }}</strong> resultados para tu búsqueda.
                @else
                    Total de activos en el sistema: <strong>{{ $total }}</strong>
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
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 100%;">
                                <span class="text-muted small">Sin imagen</span>
                            </div>
                        @endif
                        <span class="position-absolute top-0 end-0 badge bg-dark m-2 opacity-75">
                            {{ $activo->categoria->cate_nombre ?? 'S/C' }}
                        </span>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title h6 text-truncate mb-1">{{ $activo->act_nombre }}</h5>
                        <p class="card-text text-muted mb-3" style="font-size: 0.8rem;">
                            <i class="fas fa-barcode me-1"></i> {{ $activo->act_serial }}
                        </p>
                        
                        <div class="mt-auto">
                            <button type="button" class="btn btn-primary btn-sm w-100 mb-2" data-bs-toggle="modal" data-bs-target="#ficha{{ $activo->act_id }}">
                                <i class="fas fa-eye"></i> Ver Ficha
                            </button>
                            
                            <div class="d-flex gap-1">
                                <a href="{{ route('activos.edit', $activo->act_id) }}" class="btn btn-warning btn-sm flex-grow-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('activos.destroy', $activo->act_id) }}" method="POST" id="delete-form-{{ $activo->act_id }}" class="flex-grow-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm w-100" onclick="confirmarEliminacion({{ $activo->act_id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="ficha{{ $activo->act_id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Detalles del Activo</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
        @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <p class="text-muted">No se encontraron activos con esos criterios.</p>
            </div>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmarEliminacion(id) {
    Swal.fire({
        title: '¿Eliminar este dispositivo?',
        text: "Por favor, escribe el motivo o la razón de la baja para enviarlo a la papelera:",
        icon: 'warning',
        input: 'text',
        inputPlaceholder: 'Ej: Pantalla rota, Actualización técnica, Traslado definitivo...',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, borrar',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            if (!value) {
                return '¡Es obligatorio escribir un motivo para procesar la baja del activo!'
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Vinculación exacta con el ID 'delete-form-' del HTML
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
</script>