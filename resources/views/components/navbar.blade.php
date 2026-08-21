{{-- resources/views/componentes/navbar.blade.php --}}
<link rel="stylesheet" href="{{ asset('css/components/navbarStyle.css') }}">

<nav class="navbar navbar-expand-lg navbar-light bg-light">
 
    <a class="navbar-brand" href="#">SIGER</a>
 
    @if($mostrarBusqueda ?? true)
        <div class="buscador-container" style="position: relative; display: flex; align-items: center;">
            <form action="{{ $rutaBusqueda ?? route('inventario.index') }}" method="GET" style="width: 100%; margin: 0;">
                <input type="text" 
                      name="buscar"
                      id="buscador-recursos" 
                      class="form-control" 
                      placeholder="Buscar..." 
                      value="{{ request('buscar') }}" 
                      onsubmit="return false;"
                      style="padding-left: 15px; padding-right: 40px;">
                
                {{-- La lupa se pone encima del input usando posición absoluta --}}
                <i class="fas fa-search" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #6c757d; pointer-events: none;"></i>
            </form>
        </div>
    @endif

    <div class="d-flex">
        @if($mostrarRegresar ?? true)
            <a href="@yield('rutaRegresar', url()->previous())" class="btn-back-nav" title="Volver">
                <i class="fas fa-arrow-left"></i>
            </a>
        @endif

        @if($mostrarPerfil ?? true)
            <a href="#" class="nav-link"><i class="fas fa-user"></i></a>
        @endif
    </div>
</nav>