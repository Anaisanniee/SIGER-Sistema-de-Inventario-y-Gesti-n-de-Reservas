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
        {{-- TAB ACTIVOS --}}
        <div class="tab-pane fade show active" id="tab-activos">
            <div class="d-flex justify-content-between align-items-center mb-4 row-md-10 offset-md-1 px-3">
                <h2 class="mb-0">Inventario de Dispositivos</h2>
                <a href="{{ route('activos.eliminados') }}" class="btn btn-secondary btn-sm shadow-sm"><i class="fas fa-trash-alt me-1"></i> Ver Papelera</a>
                <a href="{{ route('activos.create') }}" class="btn btn-primary btn-sm">Nuevo Activo</a>
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
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title h6 text-truncate mb-1">{{ $activo->act_nombre }}</h5>
                                <p class="card-text text-muted mb-3" style="font-size: 0.8rem;"><i class="fas fa-barcode me-1"></i> {{ $activo->act_serial }}</p>
                                <button type="button" class="btn btn-primary btn-sm w-100 mb-2" data-bs-toggle="modal" data-bs-target="#fichaActivo{{ $activo->act_id }}">
                                    <i class="fas fa-eye"></i> Ver Ficha
                                </button>
                                <div class="d-flex gap-1 mt-auto">
                                    
                                    <form action="{{ route('activos.destroy', $activo->act_id) }}" method="POST" id="delete-form-{{ $activo->act_id }}" class="flex-grow-1">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm w-100" onclick="confirmarEliminacion({{ $activo->act_id }})"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5"><p>No se encontraron activos.</p></div>
                @endforelse
            </div>
        </div>

        {{-- TAB AULAS --}}
        <div class="tab-pane fade" id="tab-aulas">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Gestión de Aulas</h1>
                <div>
                    <a href="{{ route('aulas.trashed') }}" class="btn btn-outline-danger btn-sm">Ver Papelera</a>
                    <a href="{{ route('aulas.create') }}" class="btn btn-primary btn-sm">Nueva Aula</a>
                </div>
            </div>
            <div class="row">
                @forelse($aulas as $aula)
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                @if($aula->aula_foto)
                                    <img src="{{ asset('storage/' . $aula->aula_foto) }}" class="card-img-top mb-2" style="height: 150px; object-fit: cover;">
                                @endif
                                <h5 class="card-title">{{ $aula->aula_nombre }}</h5>
                                <p class="card-text text-muted mb-3" style="font-size: 0.8rem;"> <b>Capacidad:</b> {{ $aula->aula_capacidad }} personas</p>
                                <p class="card-text text-muted mb-3" style="font-size: 0.8rem;"> <b>Estado:</b> {{ $aula->aula_estado }}</p>
                                <button type="button" class="btn btn-info btn-sm w-100 mb-2" data-bs-toggle="modal" data-bs-target="#fichaAula{{ $aula->aula_id }}">Ver Ficha Técnica</button>
                                <button class="btn btn-danger btn-sm w-100" onclick="confirmarBaja({{ $aula->aula_id }})">Dar de baja</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12"><div class="alert alert-info">No hay aulas registradas.</div></div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- MODALES ACTIVOS --}}
@foreach($activos as $activo)
    <div class="modal fade" id="fichaActivo{{ $activo->act_id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Ficha Técnica</h5></div>
                <div class="modal-body">
                    @if($activo->act_foto)<div class="text-center mb-3"><img src="{{ asset('storage/' . $activo->act_foto) }}" class="img-fluid rounded" style="max-height: 200px;"></div>@endif
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Nombre:</strong> {{ $activo->act_nombre }}</li>
                        <li class="list-group-item"><strong>Serial:</strong> {{ $activo->act_serial }}</li>
                        <li class="list-group-item"><strong>Estado:</strong> {{ $activo->act_estado_fisico }}</li>
                        <li class="list-group-item">
                            <strong>Aula:</strong> {{ $activo->aula ? $activo->aula->aula_nombre : 'No asignada' }}
                        </li>
                        <li class="list-group-item">
                            <strong>Categoría:</strong> {{ $activo->categoria ? $activo->categoria->cate_nombre : 'Sin categoría' }}
                        </li>
                        <li class="list-group-item">
                            <strong>Reservable:</strong> {{ $activo->act_reservable == 1 ? 'Sí' : 'No' }}
                        </li>
                        <li class="list-group-item"><strong>Fecha:</strong> {{ $activo->act_fecha_ingreso }}</li>
                        <a href="{{ route('activos.edit', $activo->act_id) }}" class="btn btn-warning btn-sm flex-grow-1"><i class="fas fa-edit"></i></a>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endforeach

{{-- MODALES AULAS --}}
@foreach($aulas as $aula)
    <div class="modal fade" id="fichaAula{{ $aula->aula_id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Ficha Técnica: {{ $aula->aula_nombre }}</h5></div>
                <div class="modal-body">
                    @if($aula->aula_foto)<div class="text-center mb-3"><img src="{{ asset('storage/' . $aula->aula_foto) }}" class="img-fluid rounded" style="max-height: 200px;"></div>@endif
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Nombre:</strong> {{ $aula->aula_nombre }}</li>
                        <li class="list-group-item"><strong>Capacidad:</strong> {{ $aula->aula_capacidad }} personas</li>
                        <li class="list-group-item"><strong>Estado:</strong> {{ $aula->aula_estado }}</li>
                        <li class="list-group-item">
                            <strong>Tipo:</strong> {{ $aula->tipoAula ? $aula->tipoAula->tip_aula_nombre : 'Sin tipo' }}
                        </li>
                        <li class="list-group-item"><strong>Reservable:</strong> {{ $aula->aula_reservable ? 'Sí' : 'No' }}</li>
                        <a href="{{ route('aulas.edit', $aula->aula_id) }}" class="btn btn-warning">Editar Información</a>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endforeach

<form id="form-baja" method="POST" style="display: none;">
    @csrf @method('DELETE')
    <input type="hidden" name="aula_motivo_baja" id="input-motivo">
</form>
@endsection

@push('scripts')
<script>
    function confirmarEliminacion(id) {
        Swal.fire({ title: '¿Enviar a la papelera?', input: 'text', icon: 'warning', showCancelButton: true }).then((result) => {
            if (result.isConfirmed) {
                let form = document.getElementById('delete-form-' + id);
                let input = document.createElement('input'); input.type = 'hidden'; input.name = 'act_motivo_baja'; input.value = result.value;
                form.appendChild(input); form.submit();
            }
        })
    }
    function confirmarBaja(id) {
        Swal.fire({ title: 'Motivo de baja', input: 'text', icon: 'warning', showCancelButton: true }).then((result) => {
            if (result.isConfirmed) {
                let form = document.getElementById('form-baja');
                form.action = '/aulas/' + id;
                document.getElementById('input-motivo').value = result.value;
                form.submit();
            }
        });
    }
</script>
@endpush