 <html lang="en">
   <head>
   <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/components/ficha.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/body.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/navbarStyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/sidebarStyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/tarjetas.css') }}">    
    <title>siger</title>
 </head>
 <body>
        <header>
            @include ('components.navbar' , ['mostrarBusqueda' => $__env->yieldContent('mostrarBusqueda') !== 'false'])
        </header class=navbar>

                <main class="content" style="flex: 1; padding: 20px;">
                        @yield('content')
                </main>
            
 
     @stack('scripts')
 </body>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 </html>  
   
   