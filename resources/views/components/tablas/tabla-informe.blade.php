@props([
    'titulo' => 'Informe',
    'columnas' => [],
    'datos' => [],
    'urlExcel' => '#'
])

<style>
    .encabezado-tabla-acciones {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        width: 100%;
        gap: 1rem;
    }

    .btn-exportar-excel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background-color: #107c41;
        color: var(--color-fondo) !important;
        padding: 9px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.88rem;
        white-space: nowrap;
        border: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        transition: background-color 0.2s ease, transform 0.1s ease;
    }

    .btn-exportar-excel:hover {
        background-color: #0b5c30;
        transform: translateY(-1px);
    }

    .contenedor-tabla-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .contenedor-tabla-responsive table {
        width: 100%;
        white-space: nowrap;
    }

    @media (max-width: 768px) {
        .encabezado-tabla-acciones {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
        }

        .btn-exportar-excel {
            width: 100%;
            box-sizing: border-box;
        }
    }
</style>

<div class="encabezado-tabla-acciones">
    <h3 style="margin: 0; color: var(--color-principal);">{{ $titulo }}</h3>
    @if(isset($mostrarBoton) && $mostrarBoton)
        <a href="{{ $urlExcel ?? '#' }}" id="btnExportar" class="btn-exportar-excel">
            <i class="fas fa-file-excel"></i> Exportar a Excel
        </a>
    @endif
</div>

<div class="contenedor-tabla-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                @foreach ($columnas as $columna)
                    <th>{{ $columna['titulo'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($datos as $fila)
                <tr>
                    @foreach ($columnas as $columna)
                        <td>{{ $fila[$columna['campo']] ?? 'N/A' }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columnas) > 0 ? count($columnas) : 1 }}" class="text-center text-muted py-3">
                        No hay registros disponibles.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>