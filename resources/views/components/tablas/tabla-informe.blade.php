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
        color: var(--color-fondo, #ffffff) !important;
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
        border-collapse: collapse;
    }

    .contenedor-tabla-responsive th {
        background-color: var(--color-principal, #10b981);
        color: #ffffff;
        font-weight: 600;
    }

    .contenedor-tabla-responsive td, 
    .contenedor-tabla-responsive th {
        padding: 0.75rem 1rem;
        text-align: left;
        border-bottom: 1px solid var(--color-borde, #e5e7eb);
    }

    .contenedor-tabla-responsive td {
        color: var(--color-texto, #1f2937);
    }

    /* 1. Primera fila y todas las impares (1, 3, 5...) en blanco */
    .contenedor-tabla-responsive tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }

    /* 2. Filas pares (2, 4, 6...) con fondo verde pastel */
    .contenedor-tabla-responsive tbody tr:nth-child(even) {
        background-color: var(--color-disponible-pastel, #e8f5e9);
    }

    /* Efecto Hover */
    .contenedor-tabla-responsive tbody tr:hover {
        background-color: var(--color-hover-tabla, #d1e7dd);
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
    <h3 style="margin: 0; color: var(--color-principal, #10b981);">{{ $titulo }}</h3>
    @if(isset($mostrarBoton) && $mostrarBoton)
        <a href="{{ $urlExcel ?? '#' }}" id="btnExportar" class="btn-exportar-excel">
            <i class="fas fa-file-excel"></i> Exportar a Excel
        </a>
    @endif
</div>

<div class="contenedor-tabla-responsive">
    <table>
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
                        <td>
                            @if(!empty($columna['html']))
                                {!! $fila[$columna['campo']] ?? 'N/A' !!}
                            @else
                                {{ $fila[$columna['campo']] ?? 'N/A' }}
                            @endif
                        </td>
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