@props([
    'action' => '#',
    'modo' => 'recuperacion', // 'recuperacion' o 'perfil'
    'token' => '',
    'correo' => '',
    'textoBoton' => 'Actualizar Contraseña',
    'rutaCancelar' => url('/login')
])

<form action="{{ $action }}" method="POST" id="form-cambiar-password" class="text-start">
    @csrf

    @if($modo === 'perfil')
        @method('PUT')
        {{-- Campo de Contraseña Actual solo cuando se edita en el Perfil --}}
        <div class="grupo-formulario full-width-campo mb-3">
            <label for="current_password" class="label-siger">Contraseña Actual <span class="text-danger">*</span></label>
            <input type="password" id="current_password" name="current_password" required
                   placeholder="Ingresa tu contraseña actual" class="input-siger">
            @error('current_password')
                <small class="text-danger" style="color: red; margin-top: 5px; display: block;">{{ $message }}</small>
            @enderror
        </div>
    @else
        {{-- Campos Ocultos para el token de correo --}}
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="correo" value="{{ $correo }}">
    @endif

    <div class="siger-grid-formulario">
        {{-- Nueva Contraseña --}}
        <div class="grupo-formulario mb-3">
            <label for="new_password" class="label-siger">Nueva Contraseña <span class="text-danger">*</span></label>
            <div class="position-relative">
                <input type="password" id="new_password" name="password" required
                       placeholder="Mínimo 8 caracteres, mayúscula, número y símbolo" class="input-siger">
            </div>

            {{-- Barra de Fortaleza de Contraseña (Efecto Wao) --}}
            <div class="progress mt-2" style="height: 6px; background-color: #e9ecef; border-radius: 4px; overflow: hidden;">
                <div id="password-strength-bar" class="progress-bar transition-all" role="progressbar" style="width: 0%; transition: width 0.4s ease, background-color 0.4s ease;"></div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-1">
                <small id="strength-text" class="text-muted fw-bold" style="font-size: 0.75rem;">Seguridad de la contraseña</small>
            </div>

            {{-- Caja Moderna de Requisitos Interactivos --}}
            <div class="p-3 mt-2 rounded bg-light border" style="font-size: 0.82rem;">
                <strong class="d-block mb-1 text-dark">La contraseña debe cumplir con:</strong>
                <ul class="mb-0 ps-0" style="list-style: none;">
                    <li id="req-length" class="text-muted mb-1 transition-all">
                        <i class="fas fa-circle-notch fa-spin me-1 text-secondary"></i> Mínimo 8 caracteres
                    </li>
                    <li id="req-capital" class="text-muted mb-1 transition-all">
                        <i class="fas fa-circle-notch fa-spin me-1 text-secondary"></i> Una letra mayúscula y una minúscula
                    </li>
                    <li id="req-number" class="text-muted mb-1 transition-all">
                        <i class="fas fa-circle-notch fa-spin me-1 text-secondary"></i> Al menos un número
                    </li>
                    <li id="req-symbol" class="text-muted transition-all">
                        <i class="fas fa-circle-notch fa-spin me-1 text-secondary"></i> Un símbolo especial (@$!%*?&)
                    </li>
                </ul>
            </div>

            @error('password')
                <small class="text-danger" style="color: red; margin-top: 5px; display: block;">{{ $message }}</small>
            @enderror
        </div>

        {{-- Confirmación de Nueva Contraseña --}}
        <div class="grupo-formulario mb-4">
            <label for="new_password_confirmation" class="label-siger">Confirmar Nueva Contraseña <span class="text-danger">*</span></label>
            <input type="password" id="new_password_confirmation" name="password_confirmation" required
                   placeholder="Repite tu nueva contraseña" class="input-siger">
        </div>
    </div>

    <div class="siger-form-acciones d-flex gap-2 mt-3">
        <a href="{{ auth()->check() && auth()->user()->must_change_password ? '#' : $rutaCancelar }}" 
           class="w-50 text-decoration-none"
           @if(auth()->check() && auth()->user()->must_change_password) id="btn-cancelar-obligatorio" @endif>
            <x-botones.boton type="button" clase="btn-siger-accion btn-cancelar-siger w-100">
                Cancelar
            </x-botones.boton>
        </a>

        <x-botones.boton type="submit" clase="btn-siger-accion btn-verde-siger w-50">
            {{ $textoBoton }}
        </x-botones.boton>
    </div>
</form>

{{-- Formulario oculto de Logout por seguridad si es obligatorio cambiar clave --}}
@if(auth()->check() && auth()->user()->must_change_password)
<form id="logout-form-cancelar" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form-cambiar-password');
        const currentPassword = document.getElementById('current_password');
        const newPassword = document.getElementById('new_password');
        const confirmPassword = document.getElementById('new_password_confirmation');

        const reqLength = document.getElementById('req-length');
        const reqCapital = document.getElementById('req-capital');
        const reqNumber = document.getElementById('req-number');
        const reqSymbol = document.getElementById('req-symbol');
        const strengthBar = document.getElementById('password-strength-bar');
        const strengthText = document.getElementById('strength-text');

        // Interceptar el botón de cancelar obligatorio para hacer POST al logout
        const btnCancelarObligatorio = document.getElementById('btn-cancelar-obligatorio');
        if (btnCancelarObligatorio) {
            btnCancelarObligatorio.addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('logout-form-cancelar').submit();
            });
        }

        function actualizarItem(elemento, cumple) {
            const icono = elemento.querySelector('i');
            if (cumple) {
                elemento.className = 'text-success fw-bold mb-1';
                icono.className = 'fas fa-check-circle me-1 text-success';
            } else {
                elemento.className = 'text-muted mb-1';
                icono.className = 'fas fa-times-circle me-1 text-danger';
            }
        }

        if (newPassword) {
            newPassword.addEventListener('input', function() {
                const val = newPassword.value;
                let score = 0;

                // 1. Longitud
                const lenOk = val.length >= 8;
                actualizarItem(reqLength, lenOk);
                if (lenOk) score++;

                // 2. Mayúscula y minúscula
                const capOk = /[a-z]/.test(val) && /[A-Z]/.test(val);
                actualizarItem(reqCapital, capOk);
                if (capOk) score++;

                // 3. Número
                const numOk = /\d/.test(val);
                actualizarItem(reqNumber, numOk);
                if (numOk) score++;

                // 4. Símbolo
                const symOk = /[$@$!%*?&_.,-]/.test(val);
                actualizarItem(reqSymbol, symOk);
                if (symOk) score++;

                // Actualizar barra de progreso con estilo dinámico
                const porcentaje = (score / 4) * 100;
                strengthBar.style.width = porcentaje + '%';

                if (score === 0) {
                    strengthBar.style.backgroundColor = '#e9ecef';
                    strengthText.textContent = 'Seguridad de la contraseña';
                    strengthText.className = 'text-muted fw-bold';
                } else if (score <= 2) {
                    strengthBar.style.backgroundColor = '#dc3545'; // Rojo (Débil)
                    strengthText.textContent = 'Contraseña débil';
                    strengthText.className = 'text-danger fw-bold';
                } else if (score === 3) {
                    strengthBar.style.backgroundColor = '#ffc107'; // Amarillo (Media)
                    strengthText.textContent = 'Contraseña aceptable';
                    strengthText.className = 'text-warning fw-bold';
                } else {
                    strengthBar.style.backgroundColor = '#28a745'; // Verde (Fuerte / Wao)
                    strengthText.textContent = '¡Contraseña segura!';
                    strengthText.className = 'text-success fw-bold';
                }
            });
        }

        if (form) {
            form.addEventListener('submit', function(e) {
                if (newPassword.value !== confirmPassword.value) {
                    e.preventDefault();
                    alert('¡Atención! La nueva contraseña y su confirmación no coinciden.');
                    confirmPassword.focus();
                } else if (newPassword.value.length < 8) {
                    e.preventDefault();
                    alert('Por seguridad, la nueva contraseña debe contener un mínimo de 8 caracteres.');
                    newPassword.focus();
                } else if (currentPassword && currentPassword.value === newPassword.value) {
                    e.preventDefault();
                    alert('La nueva contraseña debe ser diferente a la contraseña actual.');
                    newPassword.focus();
                }
            });
        }
    });
</script>