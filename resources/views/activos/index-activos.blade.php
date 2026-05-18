 @extends('layouts.app')
@section('content')
    <h1>Activos</h1>
    <p>Esta es la página de activos, donde se muestran los recursos disponibles.</p>

    <div class="grid-container">
        @foreach($activos as $activo)
             @component('components.tarjetas.tarjeta-recurso', [
                'nombre' => $resurso['act_nombre'],
                'etiqueta' => 'Serial',
                'valor' => $resurso['act_serial']
                
            ])
            @endcomponent
        @endforeach
    </div>
    
@endsection