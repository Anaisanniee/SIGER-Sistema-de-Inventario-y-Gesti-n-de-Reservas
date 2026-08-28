<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/base/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/recuperar-contrasena.css') }}">
<<<<<<< HEAD
    <link rel="stylesheet" href="{{ asset('css/components/botones.css') }}">
=======
>>>>>>> origin/backend-Elias
    <title>Recuperar Contraseña - SIGER</title>
</head>
<body>

<<<<<<< HEAD
    {{-- Componente global de Alertas Flotantes / Notificaciones --}}
    <x-alertas.alertas-flotantes />

    <div class="siger-contenedor-login">
        <div class="tarjeta-recuperacion tarjeta-blanca-datos">
            
            <div class="perfil-header-seccion text-center">
                <div class="avatar-circulo" style="width: 60px; height: 60px; margin: 0 auto calc(var(--espaciado) * 0.75) auto;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/>
                    </svg>
                </div>

                <h1 class="perfil-titulo-principal">¿Olvidaste tu contraseña?</h1>
                <p class="perfil-subtitulo">Ingresa tu correo electrónico registrado para enviarte las instrucciones de restablecimiento.</p>
            </div>

            <main>
                <form action="/api/auth/recuperar-password" method="POST" class="grid-campos-perfil-login">
                    @csrf 
                    
                    <div class="post-form full-width-campo">
                        <label for="correo-recuperacion">Correo Electrónico</label>
                        <input 
                            type="email" 
                            id="correo-recuperacion"   
                            name="correo"
                            placeholder="ejemplo@correo.com" 
                            required
                        >
                    </div>

                    <div class="botones-accion-final full-width-campo">
                        <x-botones.boton url="{{ url('/login') }}" clase="btn-rojo text-center-link">
                            Volver al Login
                        </x-botones.boton>
                        
                        <x-botones.boton type="submit" clase="btn-perfil-guardar">
                            Enviar Enlace
                        </x-botones.boton>
                    </div>

                </form>
            </main>

        </div>
    </div>

    {{-- Script para que el botón 'X' de las alertas flotantes funcione --}}
    <script src="{{ asset('js/componentes/alertas-flotantes.js') }}"></script>
=======
   <div class="siger-contenedor-login">
    <div class="tarjeta-recuperacion tarjeta-blanca-datos">
        
        <div class="perfil-header-seccion text-center">
            <div class="avatar-circulo" style="width: 60px; height: 60px; margin: 0 auto calc(var(--espaciado) * 0.75) auto;">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/>
                </svg>
            </div>

            <h1 class="perfil-titulo-principal">¿Olvidaste tu contraseña?</h1>
            <p class="perfil-subtitulo">Ingresa tu correo electrónico registrado para enviarte las instrucciones de restablecimiento.</p>
        </div>

        @if (session('status'))
            <x-alertas.notificacion tipo="exito">
                {{ session('status') }}
            </x-alertas.notificacion>
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <x-alertas.notificacion tipo="error">
                    {{ $error }}
                </x-alertas.notificacion>
            @endforeach
        @endif

        <main>
            <form action="/api/auth/recuperar-password" method="POST" class="grid-campos-perfil-login">
                @csrf {{-- ¡No olvides el token de Laravel si es web/api protegida! --}}
                
                <div class="post-form full-width-campo">
                    <label for="correo-recuperacion">Correo Electrónico</label>
                    <input 
                        type="email" 
                        id="correo-recuperacion"   
                        name="correo"
                        placeholder="ejemplo@correo.com" 
                        required
                    >
                </div>

                <div class="botones-accion-final full-width-campo">
                    <x-botones.boton href="login" class="btn btn-rojo text-center-link">
                        Volver al Login
                    </x-botones.boton>
                    
                    <x-botones.boton type="submit" class="btn btn-perfil-guardar">
                        Enviar Enlace
                    </x-botones.boton>
                </div>

            </form>
        </main>

    </div>
    </div>
>>>>>>> origin/backend-Elias

</body>
</html>