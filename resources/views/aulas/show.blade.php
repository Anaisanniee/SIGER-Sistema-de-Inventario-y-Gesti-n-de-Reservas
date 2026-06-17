@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3>Ficha Técnica: {{ $aula->aula_nombre }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID Aula:</strong> {{ $aula->aula_id }}</p>
                    <p><strong>Capacidad:</strong> {{ $aula->aula_capacidad }} estudiantes</p>
                    <p><strong>Estado:</strong> 
                        <span class="badge {{ $aula->aula_estado == 'Disponible' ? 'bg-success' : 'bg-warning' }}">
                            {{ $aula->aula_estado }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>¿Es reservable?:</strong> {{ $aula->aula_reservable ? 'Sí' : 'No' }}</p>
                    <p><strong>Fecha de registro:</strong> {{ $aula->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
            <hr>
            <a href="{{ route('aulas.index') }}" class="btn btn-secondary">Volver al listado</a>
            <a href="{{ route('aulas.edit', $aula->aula_id) }}" class="btn btn-warning">Editar Información</a>
        </div>
    </div>
</div>
@endsection