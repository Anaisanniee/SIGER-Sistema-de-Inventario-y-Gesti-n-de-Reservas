@extends('layouts.app') 

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestión de Aulas</h1>
        <a href="{{ route('aulas.trashed') }}" class="btn btn-outline-danger">Ver Papelera</a>
        <a href="{{ route('aulas.create') }}" class="btn btn-primary">Nueva Aula</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @forelse($aulas as $aula)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
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
            <div class="col-12 text-center mt-5">
                <div class="alert alert-info">
                    No hay aulas registradas actualmente.
                </div>
            </div>
        @endforelse
    </div>
</div>

<form id="form-baja" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
    <input type="hidden" name="aula_motivo_baja" id="input-motivo">
</form>

<script>
function confirmarBaja(id) {
    Swal.fire({
        title: 'Motivo de la baja',
        input: 'text',
        inputLabel: 'Indique por qué da de baja esta aula',
        inputPlaceholder: 'Escriba aquí el motivo...',
        showCancelButton: true,
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            if (!value) {
                return 'Necesitas escribir un motivo para continuar';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.getElementById('form-baja');
            form.action = `/aulas/${id}`;
            document.getElementById('input-motivo').value = result.value;
            form.submit();
        }
    });
}
</script>
@endsection