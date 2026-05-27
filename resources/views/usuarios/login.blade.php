<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/base/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{asset('css/components/tarjetas.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6F" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4" crossorigin="anonymous"></script>
    
    <title>LoginSiger</title>
</head>

<body>

        <div class="welcome-container">
            <h1>SIGER</h1>
            <h3>Sistema de Inventario y Gestión de Reservas</h3>
        </div>
     
     <aside class="login-container">

        <h2>Iniciar Sesión</h2>
        <h4>Ingrese sus credenciales</h4>

        <form action="/login" method="POST">
            @csrf
            <div class="form-group">
                <label for="rol">Rol:</label>
                    <select id="rol" name="rol" required>
                        <option value="">--Seleccione una opción--</option>
                        <option value="re">Rector</option>
                        <option value="se">Secretario</option>
                        <option value="do">Docente</option>
                    </select>
            </div>

            <div class="form-group">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" required>

                @error('username')
                  <span style="color: red; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required autocomplete="current-password"> 
                
                @error('password')
                  <span style="color: red; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <a href="/recuperar-contraseña">¿Olvidaste tu contraseña?</a>

            <x-botones.boton
                clase="btn login-btn"
                url="#"
                type="submit">Iniciar Sesión
            </x-botones.boton>

        </form>

     </aside>

</body>
</html>