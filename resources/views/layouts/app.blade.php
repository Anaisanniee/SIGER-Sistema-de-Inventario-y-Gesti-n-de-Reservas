<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Configuración básica de metadatos y codificación de caracteres -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Estilos de bibliotecas externas (FontAwesome y Bootstrap 5) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- Hojas de estilo base del sistema -->
    <link rel="stylesheet" href="{{ asset('css/base/body.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/variables.css') }}">

    <!-- Hojas de estilo para componentes específicos -->
    <link rel="stylesheet" href="{{ asset('css/components/ficha.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/navbarStyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/sidebarStyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/tarjetas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/botones.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/carrito-reserva.css') }}">

    <!-- Pila para la inyección de estilos adicionales desde vistas hijas -->
    @stack('styles')

    <!-- Librería externa SweetAlert2 para alertas interactivas -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>SIGER</title>
</head>
<body>
    <!-- Encabezado principal y navegación -->
    <header>
        @include('components.navbar', [
            'mostrarBusqueda' => $__env->yieldContent('mostrarBusqueda') !== 'false', 
            'mostrarRegresar' => $__env->yieldContent('mostrarRegresar') !== 'false', 
            'mostrarPerfil'   => $__env->yieldContent('mostrarPerfil') !== 'false',
            'rutaBusqueda'   => $__env->yieldContent('rutaBusqueda', '#'),
            'rutaRegresar'   => $__env->yieldContent('rutaRegresar', url('/inventario'))
        ])
    </header>

    <!-- Componente Blade para alertas flotantes del sistema -->
    <x-alertas.alertas-flotantes />

    <!-- Contenedor principal donde se inyecta el contenido de cada vista -->
    <main class="content" style="flex: 1; padding: 20px;">
        @yield('content')
    </main>

    <!-- Pila para la inyección de scripts JS adicionales desde vistas hijas -->
    @stack('scripts')

    <!-- Scripts de bibliotecas externas y componentes del sistema -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/componentes/ficha-tecnica-modal.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>      
</body>
</html>