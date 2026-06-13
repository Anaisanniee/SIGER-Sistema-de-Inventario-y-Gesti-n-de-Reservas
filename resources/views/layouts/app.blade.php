 <!DOCTYPE html>
 <html lang="en">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/componentes/ficha.tecnica.css') }}">
    <link rel="stylesheet" href="{{ asset('css/body.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/componentes/navbarStyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/componentes/sidebarStyle.css') }}">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App</title>
 </head>
 <body>
        <header>
            @include('componentes.navbar')
        </header class=navbar>

                <main class="content" style="flex: 1; padding: 20px;">
                        @include('componentes.fichas.ficha-tecnica-activo')
                </main>
            
 
     @stack('scripts')
 </body>
 </html>  
   
   