<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App - SIGER</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="{{ asset('css/base/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/body.css') }}">
    <link rel="stylesheet" href="{{ asset('css/componentes/navbarStyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/componentes/sidebarStyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/componentes/ficha.tecnica.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <header class="navbar">
        @include('componentes.navbar')
    </header>

    <main class="content" style="flex: 1; padding: 20px;">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    @yield('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmarBaja(id) {
            Swal.fire({
                title: 'Motivo de la baja',
                input: 'text',
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                inputValidator: (value) => { if (!value) return 'Necesitas escribir un motivo'; }
            }).then((result) => {
                if (result.isConfirmed) {
                // Asegúrate de que el formulario 'form-baja' exista en la página
                    let form = document.getElementById('form-baja');
                    if(form) {
                        form.action = '/aulas/' + id;
                        document.getElementById('input-motivo').value = result.value;
                        form.submit();
                    } else {
                        console.error("No se encontró el formulario form-baja");
                    }
                }
            });
        }
    </script>

</body>
</html>
   