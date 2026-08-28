<<<<<<< HEAD
{{-- resources/views/components/navbar.blade.php --}}
=======
{{-- resources/views/componentes/navbar.blade.php --}}
>>>>>>> origin/backend-Elias
<link rel="stylesheet" href="{{ asset('css/components/navbarStyle.css') }}">

<nav class="navbar navbar-expand-lg navbar-light bg-light">
 
<<<<<<< HEAD
    {{-- Cambiado route('dashboard') por url('#') para evitar la excepción --}}
=======
>>>>>>> origin/backend-Elias
    <a class="navbar-brand" href="#">SIGER</a>
 
    @if($mostrarBusqueda ?? true)
        <div class="buscador-container" style="position: relative; display: flex; align-items: center;">
<<<<<<< HEAD
            <form action="{{ $rutaBusqueda ?? '#' }}" method="GET" style="width: 100%; margin: 0;">
=======
            <form action="{{ $rutaBusqueda ?? route('inventario.index') }}" method="GET" style="width: 100%; margin: 0;">
>>>>>>> origin/backend-Elias
                <input type="text" 
                      name="buscar"
                      id="buscador-recursos" 
                      class="form-control" 
                      placeholder="Buscar..." 
                      value="{{ request('buscar') }}" 
                      onsubmit="return false;"
                      style="padding-left: 15px; padding-right: 40px;">
                
<<<<<<< HEAD
=======
                {{-- La lupa se pone encima del input usando posición absoluta --}}
>>>>>>> origin/backend-Elias
                <i class="fas fa-search" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #6c757d; pointer-events: none;"></i>
            </form>
        </div>
    @endif

    <div class="d-flex">
<<<<<<< HEAD
       @if($mostrarRegresar ?? true)
=======
          @if($mostrarRegresar ?? true)
>>>>>>> origin/backend-Elias
            <a href="{{ $rutaRegresar ?? 'javascript:history.back()' }}" 
            onclick="if (!'{{ $rutaRegresar ?? '' }}') { event.preventDefault(); window.history.back(); }" 
            class="btn-back-nav" 
            title="Volver">
                <i class="fas fa-arrow-left"></i>
            </a>
        @endif

        @if($mostrarPerfil ?? true)
<<<<<<< HEAD
            <a href="{{ route('perfil') }}" class="nav-link" title="Mi Perfil">
                <i class="fas fa-user"></i>
            </a>
=======
            <a href="#" class="nav-link"><i class="fas fa-user"></i></a>
>>>>>>> origin/backend-Elias
        @endif
    </div>
</nav>