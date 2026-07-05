@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Papelera de Aulas</h1>
        <a href="{{ route('inventario.index_unificado') }}" class="btn btn-secondary">Volver al listado</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Motivo de baja</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($aulas as $aula)
                <tr>
                    <td>{{ $aula->aula_nombre }}</td>
                    <td>{{ $aula->aula_motivo_baja }}</td>
                    <td>
                        <form action="{{ route('aulas.restore', $aula->aula_id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Restaurar</button>
                        </form>

                        <form action="{{ route('aulas.forceDelete', $aula->aula_id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Borrar permanentemente?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center">La papelera está vacía.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection