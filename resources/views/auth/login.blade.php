<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SIGER</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="{{ asset('css/base/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/botones.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>

    <x-alertas.alertas-flotantes/>

    <main class="login-wrapper">
        <!-- PANEL IZQUIERDO: Marca e Identidad -->
        <section class="welcome-container">
            <!-- Formas decorativas orgánicas en fondo -->
            <div class="bg-shape shape-1"></div>
            <div class="bg-shape shape-2"></div>

            <div class="brand-content">
                <div class="brand-logo-badge">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                
                <h1 class="brand-title">SIGER</h1>
                <p class="brand-subtitle">Sistema Integrado de Inventario y Gestión de Reservas</p>

                <!-- Tarjeta destacada con beneficios del sistema -->
                <div class="brand-feature-card">
                    <div class="feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Control de activos e inventario institucional en tiempo real</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Gestión ágil de préstamos y reserva de ambientes</span>
                    </div>
                </div>
            </div>

            <footer class="brand-footer">
                <small>© {{ date('Y') }} SIGER. Todos los derechos reservados.</small>
            </footer>
        </section>

        <!-- PANEL DERECHO: Formulario de Acceso -->
        <section class="login-card-container">
            <div class="login-card">
                <header class="login-header">
                    <h2>Iniciar Sesión</h2>
                    <p>Ingrese sus credenciales corporativas para continuar</p>
                </header>

                <form method="POST" action="{{ route('login') }}" class="login-form">
                    @csrf

                    <!-- Campo Usuario -->
                    <div class="form-group">
                        <label for="username">Usuario / Cédula</label>
                        <div class="input-icon-wrapper">
                            <i class="bi bi-person input-icon"></i>
                            <input type="text" 
                                   id="username" 
                                   name="USU_CEDULA" 
                                   required 
                                   autocomplete="current-USU_CEDULA" 
                                   value="{{ old('USU_CEDULA') }}"
                                   placeholder="Número de documento">
                        </div>
                    </div>

                    <!-- Campo Contraseña con Toggle -->
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <div class="input-icon-wrapper">
                            <i class="bi bi-lock input-icon"></i>
                            <input type="password" 
                                   id="password" 
                                   name="USU_CONTRASEÑA" 
                                   required 
                                   autocomplete="current-password"
                                   placeholder="••••••••">
                            <button type="button" class="btn-toggle-password" id="togglePassword">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-options">
                        <a href="{{ route('password.request') }}" class="forgot-link">¿Olvidaste tu contraseña?</a>
                    </div>

                    <x-botones.boton
                        class="btn btn-primary-siger"
                        url=""
                        type="submit">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Iniciar Sesión
                    </x-botones.boton>
                </form>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script ligero para alternar visibilidad de la contraseña
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const toggleIcon = document.querySelector('#toggleIcon');

        if (togglePassword) {
            togglePassword.addEventListener('click', function () {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                toggleIcon.classList.toggle('bi-eye');
                toggleIcon.classList.toggle('bi-eye-slash');
            });
        }
    </script>
</body>
</html>