<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/base/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/botones.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title>LoginSiger</title>
</head>

<body>

        <div class="welcome-container">
            <h1>SIGER</h1>
            <h3>Sistema de Inventario y Gestión de Reservas</h3>
        </div>
     
     <div class="login-container">

        <h2>Iniciar Sesión</h2>
        <h4>Ingrese sus credenciales</h4>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="rol_name">Rol:</label>
                    <select id="rol_name" name="rol_name" required>
                        <option value="">--Seleccione una opción--</option>
                        <option value="Rector">Rector</option>
                        <option value="Secretaria">Secretario</option>
                        <option value="Docente">Docente</option>
                    </select>
            </div>

            <div class="form-group">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="USU_CORREO" required autocomplete="current-USU_CORREO">

                @error('username')
                  <span style="color: red; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="USU_CONTRASEÑA" required autocomplete="current-password"> 
                
                @error('password')
                  <span style="color: red; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <a href="/recuperar-contraseña">¿Olvidaste tu contraseña?</a>

            <x-botones.boton
                class="btn"
                url=""
                type="submit">Iniciar Sesión
            </x-botones.boton>
            
        </form>

</div>

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>