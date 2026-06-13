<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-trash-alt text-danger me-2"></i> Papelera de Activos</h2>
        <a href="{{ route('activos.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Inventario
        </a>
    </div>

    @if(session('mensaje'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('mensaje') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Serial</th>
                            <th>Categoría</th>
                            <th>Aula Original</th>
                            <th>Fecha de Eliminación</th>
                            <th>Motivo de Baja</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activos as $activo)
                            <tr>
                                <td>
                                    @if($activo->act_foto)
                                        <img src="{{ asset('storage/' . $activo->act_foto) }}" class="rounded shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted small" style="width: 50px; height: 50px;">
                                            Sin Foto
                                        </div>
                                    @endif
                                </td>
                                <td><strong class="text-dark">{{ $activo->act_nombre }}</strong></td>
                                <td><code class="text-secondary">{{ $activo->act_serial }}</code></td>
                                <td><span class="badge bg-secondary">{{ $activo->categoria->cate_nombre ?? 'S/C' }}</span></td>
                                <td>{{ $activo->aula->aula_nombre ?? 'No asignada' }}</td>
                                <td class="text-muted small">
                                    {{ \Carbon\Carbon::parse($activo->deleted_at)->format('d/m/Y h:i A') }}
                                </td>
                                
                                <td>
                                    @if($activo->act_motivo_baja)
                                        <span class="text-danger small fw-semibold">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $activo->act_motivo_baja }}
                                        </span>
                                    @else
                                        <span class="text-muted small fst-italic">No especificado</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <form action="{{ route('activos.restore', $activo->act_id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-undo me-1"></i> Restaurar
                                            </button>
                                        </form>

                                        <form action="{{ route('activos.forceDelete', $activo->act_id) }}" method="POST" id="force-delete-{{ $activo->act_id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmarDestruccion({{ $activo->act_id }})">
                                                <i class="fas fa-fire me-1"></i> Destruir
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fas fa-folder-open fa-3x mb-3 text-secondary"></i>
                                    <p class="mb-0">La papelera está vacía.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmarDestruccion(id) {
    Swal.fire({
        title: '¿Destruir permanentemente?',
        text: "Esta acción eliminará el registro definitivo de la base de datos y no se podrá recuperar.",
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar definitivo',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('force-delete-' + id).submit();
        }
    })
}
</script>