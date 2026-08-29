@props ([
'columnas' => [] ,// Recibiremos un arreglo con la configuración de cada columna
'datos' => [] // Recibiremos un arreglo con los datos de cada fila
])

<table class="table table-striped table-hover">
    <thead>
        <tr>
            @foreach ($columnas as $columna)
                <th>{{ $columna['titulo'] }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($datos as $fila)
            <tr>
                @foreach ($columnas as $columna)
                    <td>{{ $fila[$columna['campo']] }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>