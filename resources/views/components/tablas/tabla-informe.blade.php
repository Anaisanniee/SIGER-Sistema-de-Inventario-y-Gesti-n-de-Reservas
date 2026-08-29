@props([
    'titulo' => 'Informe', // Recibe el título dinámico de la tabla
    'columnas' => [],      // Arreglo con la configuración de las columnas
    'datos' => []          // Arreglo con las filas de datos
])

<style>
    /* Contenedor flexible para alinear el título y el botón de Excel horizontalmente */
    .encabezado-tabla-acciones {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        width: 100%;
        gap: 1rem;
    }

    /* Estilo para el botón de Exportar a Excel */
    .btn-exportar-excel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background-color: #107c41; /* Verde corporativo de Excel */
        color: #ffffff !important;
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

    /* MEDIA QUERY: Reglas para pantallas pequeñas (celulares y tablets) */
    @media (max-width: 768px) {
        .encabezado-tabla-acciones {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-exportar-excel {
            width: 100%;
        }
    }
</style>

{{-- Encabezado de la tabla con el título dinámico y el botón de Excel --}}
<div class="encabezado-tabla-acciones">
    <h3 style="margin: 0; color: var(--color-principal);">{{ $titulo }}</h3>
    <a href="#" class="btn-exportar-excel">
        <i class="fas fa-file-excel"></i> Exportar a Excel
    </a>
</div>

{{-- Renderizado dinámico de la tabla --}}
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
                <td colspan="{{ count($columnas) }}" class="text-center text-muted py-3">
                    No hay registros disponibles.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>