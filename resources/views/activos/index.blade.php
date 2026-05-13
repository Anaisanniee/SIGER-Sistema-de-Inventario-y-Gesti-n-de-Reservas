<!-- Cargar Bootstrap y FontAwesome -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="container mt-5">
    <h2 class="mb-4 text-center">Inventario de Dispositivos</h2>
    
    <!-- Barra de Búsqueda y Filtros -->
    <div class="row mb-2">
        <div class="col-md-10 offset-md-1">
            <form action="{{ route('activos.index') }}" method="GET" class="row g-2 shadow-sm p-3 bg-white rounded border">
                <!-- Buscador por texto -->
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="buscar" class="form-control border-start-0" 
                               placeholder="Nombre o serial..." value="{{ request('buscar') }}">
                    </div>
                </div>

                <!-- Filtro por Categoría -->
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

                <!-- Botones de Acción -->
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

    <!-- Contador de Resultados -->
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

    <!-- Grid de Activos -->
    <div class="row">
        @forelse($activos as $activo)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <!-- Contenedor de Imagen -->
                    <div style="height: 180px; overflow: hidden; position: relative;">
                        @if($activo->act_foto)
                            <img src="{{ asset('storage/' . $activo->act_foto) }}" class="card-img-top" style="object-fit: cover; height: 100%; width: 100%;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 100%;">
                                <span class="text-muted small">Sin imagen</span>
                            </div>
                        @endif
                        <!-- Badge de Categoría sobre la foto -->
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
                                <!-- Formulario con ID para SweetAlert2 -->
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

            <!-- MODAL: Ficha Técnica -->
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
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Nombre:</strong> <span>{{ $activo->act_nombre }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Serial:</strong> <span>{{ $activo->act_serial }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Categoría:</strong> <span class="badge bg-info text-dark">{{ $activo->categoria->cate_nombre ?? 'N/A' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Aula:</strong> <span>{{ $activo->aula->aula_nombre ?? 'No asignada' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Estado:</strong> <span>{{ $activo->act_estado_fisico }}</span>
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

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmarEliminacion(id) {
    Swal.fire({
        title: '¿Eliminar este dispositivo?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, borrar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    })
}
</script>