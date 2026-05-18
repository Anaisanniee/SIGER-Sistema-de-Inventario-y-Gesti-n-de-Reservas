{{-- resources/views/componentes/navbar.blade.php --}}
{{--Este es el código para la barra de navegación (navbar) del sistema SIGER.
 * La barra de navegación incluye el nombre del sistema, un campo de búsqueda y enlaces para el perfil y cerrar sesión.--}}

<nav class="navbar navbar-expand-lg navbar-light bg-light">
 
    <a class="navbar-brand" href="#">SIGER</a>
  

     <div class="buscador-container">
        <i class="fas fa-search search-icon"></i>
        <input type="text" class="form-control" placeholder="Buscar..." class="search-input">
     </div>
  

  <div class="d-flex">
    <a href="#" class="nav-link"><i class="fas fa-user"></i></a>
    <a href="#" class="nav-link"><i class="fas fa-gear"></i></a>
  </div>
</nav>