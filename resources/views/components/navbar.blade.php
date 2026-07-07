{{-- resources/views/componentes/navbar.blade.php --}}
{{--Este es el código para la barra de navegación (navbar) del sistema SIGER.
 * La barra de navegación incluye el nombre del sistema, un campo de búsqueda y enlaces para el perfil y cerrar sesión.--}}

<link rel="stylesheet" href="{{ asset('css/components/navbarStyle.css') }}">

<nav class="navbar navbar-expand-lg navbar-light bg-light">
 
    <a class="navbar-brand" href="#">SIGER</a>
  
     @if($mostrarBusqueda ?? true)
     <div class="buscador-container">
        <i class="fas fa-search search-icon"></i>
        <input type="text" class="form-control" placeholder="Buscar..." class="search-input">
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