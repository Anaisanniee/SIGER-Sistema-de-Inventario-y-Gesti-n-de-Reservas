<!-- Cargar Bootstrap en el head si no lo tienes en un layout principal -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
    <h2 class="mb-4 text-center">Inventario de Dispositivos</h2>
    
    <div class="row">
        @foreach($activos as $activo)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div style="height: 180px; overflow: hidden;">
                        @if($activo->act_foto)
                            <img src="{{ asset('storage/' . $activo->act_foto) }}" class="card-img-top" style="object-fit: cover; height: 100%; width: 100%;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 100%;">
                                <span class="text-muted">Sin imagen</span>
                            </div>
                        @endif
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-truncate">{{ $activo->act_nombre }}</h5>
                        <p class="card-text text-muted small">Serial: {{ $activo->act_serial }}</p>
                        
                        <div class="mt-auto">
                            <!-- El target debe coincidir con el ID del modal -->
                            <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#ficha{{ $activo->act_id }}">
                                Ver Ficha Técnica
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL: Asegúrate de que el ID sea ficha + el ID numérico -->
            <div class="modal fade" id="ficha{{ $activo->act_id }}" tabindex="-1" aria-labelledby="label{{ $activo->act_id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="label{{ $activo->act_id }}">Ficha Técnica</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            @if($activo->act_foto)
                                <img src="{{ asset('storage/' . $activo->act_foto) }}" class="img-fluid rounded mb-3" style="max-height: 250px;">
                            @endif
                            <ul class="list-group list-group-flush text-start">
                                <li class="list-group-item"><strong>Nombre:</strong> {{ $activo->act_nombre }}</li>
                                <li class="list-group-item"><strong>Serial:</strong> {{ $activo->act_serial }}</li>
                                <li class="list-group-item"><strong>Estado:</strong> {{ $activo->act_estado_fisico }}</li>
                                <li class="list-group-item"><strong>Ubicación:</strong> {{ $activo->aula->AULA_NOMBRE ?? 'No asignada' }}</li>
                                <li class="list-group-item"><strong>Fecha de Ingreso:</strong> {{ $activo->act_fecha_ingreso }}</li>
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Script de Bootstrap obligatorio para los modales -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>